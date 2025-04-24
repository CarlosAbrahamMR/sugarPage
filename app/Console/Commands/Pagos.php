<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Pagos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generar:pagos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera los pagos de suscripcionesS';

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
     * @return int
     */
    public function handle()
    {
        $data['confirmation_code']='asdfg';
        $data['name'] =  'daniel cron';
            $data['email'] =  'pantaleondan@gmail.com';

            $data['appName'] = env('APP_NAME', 'MysugarFan');
            Mail::send('email.confirmation', $data, function($message) use ($data) {
                $message->to($data['email'], $data['name'])->subject('Por favor confirma tu correo');
            });
        
        $this->info('Los mensajes de felicitacion han sido enviados correctamente');
    }
}
