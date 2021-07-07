<?php
namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;

use App\Models\Auth\User;
use App\Services\MailService;
use Carbon\Carbon;

class ResendActivationEmail extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xapp:send-mail {start? : Starting date of User registration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails with Account activation link';

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
        parent::handle();

        // Setup Carbon language
        $lang = 'pt_BR';

        // Current date
        $today = Carbon::now('America/Sao_Paulo')->locale($lang);
		$start = $this->argument('start') ?? '5 days';
		$date  = $today->sub($start);
		
		$this->line('>> Starting at ' . $date->format('d.m.Y') );
		
		// Select Users registered from given date that not activate accounts yet
		$users = User::where('id', '>', 1)->where('created_at', '>=', $date)->whereNull('email_verified_at')->get();
		
		foreach( $users as $user )
        {
			$this->write('>> Send email to user #' . $user->id .' ['. $user->username .'] ... ');
			
            // Send mail with activation link
			MailService::sendRegister( $user->fullname, $user->username, $user->validation_token );
			//$this->line('MailService::sendRegister( "'. $user->fullname .'", "'. $user->username .'", "'. $user->validation_token .'" )');
			
            //
            $this->line('OK');
			
			$this->wait();
        }

        $this->ends();
    }
}
