<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Cache;

class SendQuotesDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quotes:send_daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the quotes to the users';

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
        $quote = \App\Http\Controllers\APIWallpapersController::getQuote();
        $cache_quote = Cache::where( 'key', 'quote-quote' )->first();
        $cache_name = Cache::where( 'key', 'quote-name' )->first();

        if( !$cache_quote )
            $cache_quote = Cache::create( [ 'key' => 'quote-quote', 'content' => $quote->quote, 'hash' => time() ] );
        else{
            $cache_quote->content = $quote->quote;
            $cache_quote->save();
        }

        if( !$cache_name )
            $cache_name = Cache::create( [ 'key' => 'quote-name', 'content' => $quote->category->name, 'hash' => time() ] );
        else{
            $cache_name->content = $quote->category->name;
            $cache_name->save();
        }

        $category = @$quote->category;
        $category_name = @$category->name;
        $wallpaper = @$category->wallpapers()->where( 'thumb_image_file_id', '<>', null )->inRandomOrder()->first();
        $thumb = @$wallpaper->thumbImageFile;

        foreach( \App\User::where( 'notification_key', '<>', null )->get() as $u ){
            $anonymous = \App\Anonymous::where( 'notification_key', $u->notification_key )->first();
            if( $anonymous ){
                $this->warn( 'removed user #' . $u->id . ' from anonymous' );
                @$anonymous->cleanup();
            }

            $ret = \App\Helper\sendPushNotification(
                $u->notification_key,
                $category_name,
                \App\Helper\getSnippet( $quote->quote ) . '…',
                [ 'type' => 2, 'img_url' => !empty($thumb) ? $thumb->fullURL() : '' ] );
            $ret_json = json_decode( $ret );
            if( @$ret_json->failure >= 1 ){
                $u->notification_key = null;
                $u->save();
                $this->error( 'error sending quote of the day to user #' . $u->id );
            }
            else
                $this->info( 'sent quote of the day to user #' . $u->id );
        }

        foreach( \App\Anonymous::where( 'notification_key', '<>', null )->get() as $a ){
            $ret = \App\Helper\sendPushNotification(
                $a->notification_key,
                $category_name,
                \App\Helper\getSnippet( $quote->quote ) . '…',
                [ 'type' => 2, 'img_url' => !empty($thumb) ? $thumb->fullURL() : '' ] );
            $ret_json = json_decode( $ret );
            if( @$ret_json->failure >= 1 ){
                $a->notification_key = null;
                $a->save();
                $this->error( 'error sending quote of the day to anonymous #' . $a->id );
            }
            else
                $this->info( 'sent quote of the day to anonymous #' . $a->id );
        }        
    }
}
