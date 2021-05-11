<?php

namespace App\Service;

use phpseclib3\Net\SFTP;
use Psr\Log\LoggerInterface;

class SftpService
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Undocumented function
     *
     * @param array $host
     * @param array $auth
     * @param array $files
     * @return void
     */
    public function uploadFiles(array $host, array $auth, array $files)
    {
        # https://qiita.com/ngyuki/items/a284be0adf78df9e68d4
        $sftp = new SFTP($host['hostname'], $host['port']);

        try {
            if (!$sftp->login($auth['username'], $auth['password'])) {
                $this->logger->info("failure auth.");
                return;
            }

            $this->logger->info($sftp->pwd());

            foreach ($files as $file) {
                $this->logger->debug("{$file['from']} -> {$file['to']}");
                if ($sftp->put($file['to'], $file['from'], SFTP::SOURCE_LOCAL_FILE)) {
                    $this->logger->info("put success. {$file['to']}");
                } else {
                    # https://chrisguitarguy.com/2017/10/24/phpseclib-sftp-error-handling/
                    $last = $sftp->getLastSFTPError();
                    $this->logger->error("put failure. {$last}, {$file['from']} -> {$file['to']}");
                }
            }
        } finally {
            $sftp->disconnect();
        }
    }

    /**
     * Undocumented function
     *
     * @param array $host
     * @param array $auth
     * @param array $files
     * @return void
     */
    public function downloadFiles(array $host, array $auth, array $files)
    {
        # https://qiita.com/ngyuki/items/a284be0adf78df9e68d4
        $sftp = new SFTP($host['hostname'], $host['port']);

        try {
            if (!$sftp->login($auth['username'], $auth['password'])) {
                $this->logger->info("failure auth.");
                return;
            }

            foreach ($files as $file) {
                $sftp->get($file['from'], $file['to']);
            }
        } finally {
            $sftp->disconnect();
        }
    }
}
