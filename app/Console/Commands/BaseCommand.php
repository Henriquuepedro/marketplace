<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class BaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'xapp:base';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Base command for all other commands. Do not call!';

    protected static $dash  = '-----------------------------------------------------------------------';
    protected static $ddash = '=======================================================================';
    protected static $edash = '============================== E N D ==================================';

    /** @var float Holds the command execution time. */
    protected $time;

    /** @var int Holds the amount of processed lines / rows / registers. */
    protected $count;

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Define CLI constant
        if( ! defined('CLI') )
            define('CLI', true);

        // Get current OS
        $os_name = strtolower( php_uname() );
        //echo $os_name;

        if( Str::contains($os_name, 'windows') )
        {
            //echo 'This is a Windows machine';
            exec('chcp 65001');
        }

        $this->time  = microtime(true);
        $this->count = 0;
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $this->starts();
    }

    /**
     * Display the Command header.
     */
    public function starts()
    {
        $this->spc();
        $this->drule();
        $this->info( $this->description );
        $this->line( 'Environment: ' . app()->environment() );
        $this->drule();
        $this->spc();
    }

    /**
     * Display a Caption or Subtitle
     * @param string $text Text to be displayed.
     */
    public function caption( string $text )
    {
        $this->spc();
        $this->rule();
        $this->line( $text );
        $this->rule();
        $this->spc();
    }

    /**
     * Display the Command footer
     */
    public function ends()
    {
        // Get command execution time
        $sec = $this->ellapsedTime();

        $this->spc();
        $this->drule();

        // Register/lines/rows processed
        if( $this->count > 0 )
        {
            $this->info( 'Total of records/lines/rows processed: ' . $this->count );
            $this->spc();
        }

        // Ellapsed time
        $this->info( 'Command execution time: ' . $sec . ' seconds' );

        $this->drule();
        $this->spc();
        $this->line( self::$edash );
        $this->spc();
    }

    /**
     * Writes down an empty line - Alias for space()
     */
    public function spc()
    {
        $this->space();
    }

    /**
     * Writes down an empty line.
     */
    public function space()
    {
        $this->line( ' ' );
    }

    /**
     * Writes down a single dash.
     */
    public function rule()
    {
        $this->line( self::$dash );
    }

    /**
     * Writes down a double dash - Alias for doubleRule()
     */
    public function drule()
    {
        $this->doubleRule();
    }

    /**
     * Writes down a double dash.
     */
    public function doubleRule()
    {
        $this->line( self::$ddash );
    }

    /**
     * Writes a line with space before and after.
     * @param mixed $content
     */
    public function spacedLine( $content )
    {
        $this->space();
        $this->line( $content );
        $this->space();
    }

    /**
     * Writes a line with bullet prefix.
     * @param mixed $content Content to be displayed.
     * @param int $level    Level of bullets. Defaults to 0
     * @param string $bullet   The char to be used as bullet. Defaults to '>'
     */
    public function ln( $content, $level = 0, $bullet = '>' )
    {
        $prefix = str_repeat( $bullet, $level ) . ( ($level > 0) ? ' ' : '' );

        $this->line( $prefix . $content );
    }

    /**
     * Writes without break line.
     * @param mixed $what
     */
    public function write( $what )
    {
        $this->output->write( $what, false );
    }

    /**
     * Replace previous line with given content.
     * @param mixed $what
     */
    public function rewrite( $what )
    {
        $this->output->write( "\r", false, \Symfony\Component\Console\Output\OutputInterface::OUTPUT_RAW);
        $this->output->write( $what, false );
    }

    /**
     * Increment the register/line/row counter - Alias for increment()
     */
    public function inc()
    {
        $this->increment();
    }

    /**
     * Increment the register/line/row counter.
     */
    public function increment()
    {
        $this->count++;
    }

    /**
     * Delays the command execution
     * @param int $milliseconds
     */
    public function wait( $milliseconds = 500 )
    {
        usleep( $milliseconds * 1000 );
    }

    /**
     * Returns the command execution time formatted as seconds
     * @return float
     */
    protected function ellapsedTime()
    {
        $end = microtime(true);
        $ellapsed = $end - $this->time;

        return round( $ellapsed , 2 );
    }
}
