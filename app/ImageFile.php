<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageFile extends Model
{
    protected $table = 'image_files';

    protected $fillable = [
    	'public_url',
    	'original_name',
        'cloud_disk_path',
        'cloud_public_url',
        'size',
        'mime'
    ];

    const MIME_NOT_SET = 0;

    const MIME_JPEG = 1;
    const MIME_BMP = 2;
    const MIME_PNG = 3;
    const MIME_GIF = 4;
    const MIME_IMG_OTHER = 5;

    const MIME_QT = 6;
    const MIME_MP4 = 7;
    const MIME_3GPP = 8;
    const MIME_AVI = 9;
    const MIME_VIDEO_OTHER = 10;

    public static $mimes = [
        null => 'not set',
        self::MIME_NOT_SET => 'not set',
        self::MIME_JPEG => 'jpg',
        self::MIME_BMP => 'bmp',
        self::MIME_PNG => 'png',
        self::MIME_GIF => 'gif',
        self::MIME_IMG_OTHER => 'image other',
        self::MIME_QT => 'qt/mov',
        self::MIME_MP4 => 'mp4',
        self::MIME_3GPP => '3gp',
        self::MIME_AVI => 'avi',
        self::MIME_VIDEO_OTHER => 'video other',
    ];

    public static $images = [ self::MIME_JPEG, self::MIME_BMP, self::MIME_PNG, self::MIME_GIF, self::MIME_IMG_OTHER ];
    public static $videos = [ self::MIME_QT, self::MIME_MP4, self::MIME_3GPP, self::MIME_AVI, self::MIME_VIDEO_OTHER ];

    public static $rules = [
        'image' => 'mimes:jpeg,bmp,png,gif,video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi,mp4,mov,ogg,qt|max:50000'
    ];
    public static function boot()
    {
        parent::boot();

        self::created( function( $model ){
            $model->setMime();
        } );
    }

    public function fullURL(){        
        return $this->cloud_public_url ?? asset( $this->public_url );
    }

    public function fullLocalURL(){
        return asset( $this->public_url );
    }

    public function getURL(){
        //if( $this->cloud_public_url )
        //    return $this->cloud_public_url;
        
        return asset( $this->public_url );
    }

    public static function connectFTP(){
        $ftp = new \FtpClient\FtpClient();
        $ftp->connect( env( 'CLOUD_FTP_HOST' ) );
        $ftp->login(
            env( 'CLOUD_FTP_USER' ),
            env( 'CLOUD_FTP_PASS' ) );
        $ftp->pasv( true );
        return $ftp;
    }

    public function uploadToCloud( $ftp = null ){
        if( $this->cloud_disk_path )
            return true;

        if( !$ftp )
            $ftp = self::connectFTP();

        $remote_path = '/Wallpapers/' . sprintf( '%02d', mt_rand( 0, 99 ) ) . '/' . time() . $this->original_name;

        if( $ftp->put( $remote_path, public_path() . $this->public_url, FTP_BINARY ) ){
            $this->cloud_disk_path = $remote_path;
            $this->cloud_public_url = 'https://' . env( 'CLOUD_FTP_HOST' ) . $remote_path;
            $this->save();
            return true;
        }

        return false;
    }

    public function deleteFromCloud( $ftp = null ){
        if( !$this->cloud_disk_path )
            return true;

        if( !$ftp )
            $ftp = self::connectFTP();

        if( $ftp->remove( $this->cloud_disk_path ) ){
            $this->cloud_disk_path = null;
            $this->cloud_public_url = null;
            $this->save();
            return true;
        }

        return false;
    }

    public function category(){
        return $this->hasOne( '\App\Category' );
    }

    public function wallpaper(){
        return $this->hasOne( '\App\Wallpaper' );
    }

    public function wallpaperThumb(){
        return $this->hasOne( '\App\Wallpaper', 'thumb_image_file_id' );
    }

    public function wallpaperVideo(){
        return $this->hasOne( '\App\Wallpaper', 'video_file_id' );
    }

    public function isImage(){
        if( in_array( $this->mime, self::$images ) )
            return true;
        return false;
    }

    public function isVideo(){
        if( in_array( $this->mime, self::$videos ) )
            return true;
        return false;
    }

    public function setMime(){
        $ret = mime_content_type( public_path() . $this->public_url );

        if( $ret ){
            if( $ret == 'image/jpeg' )
                $this->mime = self::MIME_JPEG;
            elseif( $ret == 'image/png' )
                $this->mime = self::MIME_PNG;
            elseif( $ret == 'image/bmp' )
                $this->mime = self::MIME_BMP;
            elseif( $ret == 'image/gif' )
                $this->mime = self::MIME_GIF;
            elseif( strpos( $ret, 'image/' ) === 0 )
                $this->mime = self::MIME_IMG_OTHER;
            elseif( $ret == 'video/quicktime' )
                $this->mime = self::MIME_QT;
            elseif( $ret == 'video/mp4' )
                $this->mime = self::MIME_MP4;
            elseif( $ret == 'video/3gpp' )
                $this->mime = self::MIME_3GPP;
            elseif( $ret == 'video/x-msvideo' )
                $this->mime = self::MIME_AVI;
            elseif( strpos( $ret, 'video/' ) === 0 )
                $this->mime = self::MIME_VIDEO_OTHER;
        }
        else
            $this->mime = self::MIME_NOT_SET;

        $this->save();
    }

    public function cleanup(){
        $this->deleteFromCloud();

        if( $category = $this->category ){
            $category->discardImageFile();
            $category->save();
        }

        if( $wallpaper = $this->wallpaper ){
            $wallpaper->discardImageFile();
            $wallpaper->save();
        }

        if( $wallpaper = $this->wallpaperThumb ){
            $wallpaper->discardThumbImageFile();
            $wallpaper->save();
        }

        if( $wallpaper = $this->wallpaperVideo ){
            $wallpaper->discardVideoFile();
            $wallpaper->save();
        }

        try{
            if( $this->public_url )
                unlink( public_path() . $this->public_url );
        }
        catch( \Exception $e ){}

        $this->public_url = null;
        $this->save();

        return $this->forceDelete();
    }
}
