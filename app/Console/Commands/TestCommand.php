<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use seregazhuk\PinterestBot\Factories\PinterestBot;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Do testing stuff';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $it = 0;
        $this->info( 'started' );
        $ftp = new \FtpClient\FtpClient();
        $ftp->connect( env( 'CLOUD_FTP_HOST' ) );
        $res = $ftp->login(
            env( 'CLOUD_FTP_USER' ),
            env( 'CLOUD_FTP_PASS' ) );
        $ftp->pasv( true );

        if( $res )
            $this->info( 'connected to FTP' );
        else
            $this->error( 'error connecting to FTP' );
 /*
        for( $i = 0; $i < 100; $i++ ){
            $ftp->mkdir( 'APPS/WALLPAPERS_RAP/' . sprintf( '%02d', $i ) );
        }
*/
        foreach( \App\ImageFile::all() as $img ){
            $it++;
            //$img = $c->imageFile;
            if( $img->uploadToCloud( $ftp ) ){
                $this->info( $img->cloud_public_url );
            }
            else{
                $this->error( 'error saving to FTP' );
                if( $it > 150 ){
                    $ftp = new \FtpClient\FtpClient();
                    $ftp->connect( env( 'CLOUD_FTP_HOST' ) );
                    $res = $ftp->login(
                        env( 'CLOUD_FTP_USER' ),
                        env( 'CLOUD_FTP_PASS' ) );
                    $ftp->pasv( true );
                }
            }
        }

        $this->info( 'finished' );
        return;
/*
*/
        $this->info( 'started' );
        $ftp = new \FtpClient\FtpClient();
        $ftp->connect( env( 'CLOUD_FTP_HOST' ) );
        $res = $ftp->login(
            env( 'CLOUD_FTP_USER' ),
            env( 'CLOUD_FTP_PASS' ) );
        $ftp->pasv( true );

        if( $res )
            $this->info( 'connected to FTP' );
        else
            $this->error( 'error connecting to FTP' );
/*
        for( $i = 0; $i < 100; $i++ ){
            $ftp->mkdir( 'APPS/WALLPAPERS_RAP/' . sprintf( '%02d', $i ) );
        }
*/
        //foreach( \App\Category::all() as $c ){
        foreach( \App\ImageFile::all() as $img ){
            //$img = $c->imageFile;
            if( $img->uploadToCloudDisc( $ftp ) ){
                $this->info( $img->cloud_public_url );
            }
            else
                $this->error( 'error saving to FTP' );
        }

        $this->info( 'finished' );
    }
}
