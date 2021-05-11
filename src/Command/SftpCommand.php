<?php

namespace App\Command;

use App\Service\SftpService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SftpCommand extends Command
{
    protected static $defaultName = 'import:sftp';
    protected static $defaultDescription = 'import sftp data.';

    private SftpService $sftpService;

    private string $projectDir;

    public function __construct(
        string $projectDir,
        SftpService $sftpService
    ) {
        parent::__construct();

        $this->projectDir = $projectDir;
        $this->sftpService = $sftpService;
    }

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // sfpt server
        $host = [
            'hostname' => 'symfony-sftp',
            'port' => '22'
        ];

        $auth = [
            'username' => 'foo',
            'password' => 'password'
        ];

        $files = [
            [
                'from' => "{$this->projectDir}/var/log/product.csv",
                // ハマった. sftp user の見えるディレクトリ構成を把握してね.
                'to' => '/upload/product.csv'
            ],
        ];

        $this->sftpService->uploadFiles($host, $auth, $files);

        // scp server
        $host = [
            'hostname' => 'symfony-scp',
            'port' => '22'
        ];

        $auth = [
            'username' => 'foo',
            'password' => 'password'
        ];

        $files = [
            [
                'from' => "{$this->projectDir}/var/log/product.csv",
                'to' => '/home/foo/upload/product.csv'
            ],
        ];

        $this->sftpService->uploadFiles($host, $auth, $files);

        return Command::SUCCESS;
    }
}
