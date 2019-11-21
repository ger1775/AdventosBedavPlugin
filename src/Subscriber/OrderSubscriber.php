<?php declare(strict_types = 1);

/**
 *
 *
 *
*/

namespace AdventosBedavPlugin\Subscriber;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\Checkout\Order\OrderEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\Serializer\Encoder\XmlEncoder;


class OrderSubscriber implements EventSubscriberInterface {

    private $systemConfigService;

    private $log;

    public function __construct(SystemConfigService $systemConfigService ) {
        $this->systemConfigService = $systemConfigService;

        $this->log = new Logger('AdventosBedav');
        $this->log->pushHandler(new StreamHandler('adventos.log', Logger::WARNING));
    }

    public static function getSubscribedEvents(): array
    {
        return[
            OrderEvents::ORDER_WRITTEN_EVENT => 'onOrderStateWritten'
        ];
    }


    public function onOrderStateWritten(EntityWrittenEvent $event)
    {
        if ( $this->systemConfigService->get('AdventosBedavPlugin.config.active')) {
            $channelId = $this->systemConfigService->get('AdventosBedavPlugin.config.channelId');

            $filePath = __DIR__ . "/var/tmp/SalesOrder.xml";
            $this->log->addWarning($filePath);
            $encoder = new XmlEncoder();

            $this->log->addWarning( print_r($event->getIds()[0] , true) );
        }
    }
}
