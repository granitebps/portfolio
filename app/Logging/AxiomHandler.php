<?php

namespace App\Logging;

use Illuminate\Support\Facades\Http;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Monolog\Formatter\FormatterInterface;

class AxiomHandler extends AbstractProcessingHandler
{
    private $apiToken;
    private $dataset;
    private $axiomUrl;
    protected static $promises = [];

    public function __construct($level = Level::Debug, bool $bubble = true, $apiToken = null, $dataset = null)
    {
        parent::__construct($level, $bubble);
        $this->apiToken = $apiToken;
        $this->dataset = $dataset;
        $this->axiomUrl = 'https://api.axiom.co';
    }

    protected function write(LogRecord $record): void
    {
        $data = [
            'message' => $record->message,
            'context' => $record->context,
            'level' => $record->level->getName(),
            'channel' => $record->channel,
            'extra' => $record->extra,
            'formatted' => $record->formatted,
            'app_url' => config('app.url')
        ];

        Http::withToken($this->apiToken)
            ->post("{$this->axiomUrl}/v1/datasets/{$this->dataset}/ingest", [$data]);
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new \Monolog\Formatter\JsonFormatter();
    }
}
