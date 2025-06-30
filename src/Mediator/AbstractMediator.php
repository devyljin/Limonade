<?php

namespace Agrume\Limonade\Mediator;
use Agrume\Limonade\Mediator\MediatorEventInterface;
use Agrume\Limonade\Mediator\MediatorInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractMediator
 *
 * Base implementation of the Mediator pattern.
 * Provides default notification logic for handling MediatorEventInterface events.
 *
 * Intended to be extended by concrete mediators that coordinate communication
 * between various components, optionally adding more event types or services.
 *
 * @package Agrume\Limonade\Mediator
 */
abstract class AbstractMediator implements MediatorInterface
{
    /**
     * @var LoggerInterface|null Logger used for handling log/debug events.
     * This should be set by the concrete class.
     */
    protected ?LoggerInterface $logger = null;
    /**
     * Dispatches an event to the mediator.
     *
     * This is a wrapper around `notify()` that extracts the sender from the event object.
     *
     * @param MediatorEventInterface $event The event to dispatch
     * @return void
     */
    public function nautofy(MediatorEventInterface $event): void {
        $this->notify($event->getSender(), $event);
    }
    /**
     * Handles event dispatch logic based on the event's name.
     *
     * The method uses a match expression to determine how to handle known events
     * like "log" or "debug". Default behavior is currently a no-op.
     *
     * @param object $sender The component that triggered the event
     * @param MediatorEventInterface $event The event object
     * @return void
     */
    public function notify(object $sender, MediatorEventInterface $event){
        match($event->getName()){
            "log" => $this->logger->log($event->getLogLevel(), $event->getMessage()),
            "debug" => $this->logger->log($event->getLogLevel(), $event->getMessage()),
            default => function (string $event){
            },
        };
    }
}

//                                                                 This is Pepito
//                                                                 As you can see, Pepito using a mediator
//                                                                 Be like Pepito, do it right.


//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⡴⠒⠦⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⡴⠚⠒⣆⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⠷⡾⣖⡌⡳⣆⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⠞⣡⡿⣄⢀⢹⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣼⢰⠡⠮⣧⠈⠊⠷⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣼⠃⡽⠁⠈⡾⢸⢸⠆⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⡈⡏⠁⣾⣧⠀⢠⠙⣦⠀⢀⣀⣀⣀⣀⣀⣀⠀⢠⡟⢠⠃⡀⠍⢻⠀⡀⣸⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠘⣇⢳⡀⢈⣿⣟⠰⠀⡘⠛⠉⠉⠁⠠⠀⠉⠉⠙⠻⠀⡾⢈⠝⢯⠁⠀⢠⡏⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢹⣆⠉⣹⣔⣇⠆⠀⠀⠀⠀⠀⠀⠐⠀⠀⠀⠀⠀⠀⠑⢎⠀⠘⡿⢣⣾⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⡄⠀⠁⡹⠇⠀⠀⠀⢀⣀⠀⠀⠈⡆⠀⠀⣀⠀⠀⠀⠀⠙⡋⠀⠀⡿⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠙⢦⡊⠀⠀⠀⠄⠊⠠⠀⢀⠀⠀⡁⠀⠁⢠⡄⠑⠀⠀⠀⠈⢶⠟⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠘⣧⠀⠀⢀⣴⣖⣶⡀⠸⠀⠀⡁⠀⠀⣼⣗⣶⣄⢀⠀⢀⡞⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢹⠀⠀⠈⠹⠿⠿⠵⠀⠀⠀⠀⠀⠐⠺⠿⠿⠋⠈⠀⣸⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⠀⠀⠀⠄⠀⠀⠀⠀⠀⣼⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣸⣇⠀⡀⠀⠀⠀⡀⢠⣾⣿⣿⣷⡄⠐⡀⠀⠀⠀⢀⡟⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⠀⣠⣤⡀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢰⡏⠹⣄⠐⢈⣅⠀⠀⠈⠻⣾⣿⡾⠃⠀⠇⡼⠆⣡⠞⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⡀⡞⣢⠷⠋⣵⠄⠹⡄⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣠⠿⣣⡀⠈⢆⠀⠹⣳⣤⣀⣀⣤⣧⣄⣀⣤⡾⠃⣾⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⡵⡞⠹⣧⠘⠫⣀⣴⡀⠙⣆
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢰⠟⠁⠀⠱⣝⢦⡈⠆⠀⠈⠙⢟⢦⣀⣀⣴⣹⠉⠐⢱⠇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣰⠇⠘⠿⠨⣐⡴⢾⡇⣘⡷⣞⡁
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⡴⠋⠀⠀⠀⠀⠈⠳⢍⡺⢤⣀⠀⠀⠉⠛⠙⠉⠁⠀⢀⣾⡄⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⢴⠚⡫⡀⡐⠔⠉⢸⣷⡼⢻⢻⠀⠓⠃
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⡴⠋⠀⠀⠀⠀⠀⠀⠀⠀⠀⠉⠓⠴⣍⣽⡒⠒⠒⣒⣒⣭⡵⢾⡀⠀⠀⠀⠀⠀⢀⣠⠶⣋⠡⡁⢧⣌⡾⠖⠛⠛⠋⠸⠽⠀⠁⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⠞⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠉⠉⠉⠀⠀⠀⠈⢧⠀⠀⢀⣠⠶⢯⠑⢆⢈⣧⠾⠚⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⢀⡴⠊⠁⠀⠀⠀⠀⠀⠀⠀⠀⠃⠀⠀⢀⣠⠖⠚⠛⠓⠶⣄⡀⠀⠀⠀⠀⠀⠀⢈⣷⣾⣯⣧⣛⠦⣳⡶⠋⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⣠⠟⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠀⣀⡴⠋⠀⠀⠀⠀⠀⠀⠈⠻⣄⠀⠀⢀⣤⢾⡋⣴⡇⠙⠀⠈⠛⢧⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⣟⠀⠀⠀⠀⠀⠀⠈⠑⢶⣖⠚⠋⠉⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣘⣷⠞⣯⠸⣖⣙⣴⢿⣄⠀⠀⠀⠀⢷⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⢀⣤⠞⠛⢦⣀⠀⠠⡀⠀⠀⠀⠀⠙⣦⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⣴⢟⢣⣲⣿⢷⣩⠶⠋⠀⠀⠉⠳⠦⠄⠀⡾⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⣴⠋⠀⠀⠀⠀⠉⠙⠦⣴⡄⠀⠀⠀⠀⠈⢳⡀⠀⠀⡤⣲⣶⣴⣶⣾⢻⡺⡱⣳⡷⠚⢻⣅⠀⠀⠀⠀⠀⠀⠀⢀⡾⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⣸⠃⠀⠀⠀⠀⠀⠀⠀⠀⠀⢻⡀⠀⠆⠀⠢⢘⡇⠀⡌⣿⣿⣿⣿⣿⣿⣾⡴⠛⠁⠀⠀⠀⢻⡆⣹⠲⠦⠤⠴⠞⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⡟⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠙⢦⡴⣤⠴⠞⠀⠠⢓⣿⣿⣿⣿⣿⣿⡻⡄⠀⠀⠀⠀⠀⠀⣿⡟⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⣧⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢰⠊⣢⡀⠄⠀⠀⠄⡪⠍⢟⡿⠿⣿⡿⠃⠀⠀⠀⠀⠀⠀⢀⡿⡧⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⢻⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢫⡳⣿⣅⠪⠐⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⣠⡴⠛⢠⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠈⣧⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠙⡿⡟⢦⠀⠀⠀⠀⠀⠀⠀⠀⢀⣤⡶⠟⠛⠋⠁⠀⠀⣼⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠘⣧⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠑⠊⠀⠀⠀⠀⠀⠀⠀⣰⡿⠋⠀⠀⠀⠀⠀⠀⠀⠉⠙⢶⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠘⢦⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣼⡟⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⣧⠀⠀⣼⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠘⢧⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣰⡿⠁⠀⠀⠀⠀⠀⠀⠀⠀⡆⠀⠀⠀⡿⣀⣴⡏⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠈⠻⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣴⣿⠁⠀⠀⠀⠀⠀⠀⠀⠀⡜⠀⠀⠀⢰⡟⢩⡾⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⢻⡳⣄⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⡾⠋⢸⠀⠀⠀⠀⠀⠀⠀⠀⡴⠁⠀⠀⠀⣼⣤⠏⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠳⡌⠙⠶⣤⣀⡀⠀⠀⠀⣀⣠⠶⠋⠀⠀⢸⠀⠀⠀⠀⠀⠀⣠⣾⠀⠀⠀⠀⢰⠛⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⠀⠉⠳⢤⣿⠈⠉⠙⢛⡿⠻⢤⣀⠀⠀⠀⠘⠀⠀⠀⢀⣠⠾⠋⠘⡆⠀⠀⠀⢸⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠀⣠⡤⠤⡴⠇⠀⠀⢀⡾⠀⠀⢀⣨⠿⠓⠶⠶⠶⠒⠻⣏⡀⠀⠀⠀⢹⡄⠀⠀⠘⢦⣤⠴⠦⢤⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⣰⠇⠐⠀⠀⡀⠀⠀⣼⠷⠖⠚⠋⠁⠀⠀⠀⠀⠀⠀⠀⠀⠉⠛⠒⠲⠶⢷⠀⠀⠀⠀⠐⠄⠀⠀⢹⡆⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
//                                            ⠀⠀⠀⠀⠀⠀⠹⢦⣥⣤⣠⣥⣤⠴⠃⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠓⠦⠤⠤⣤⣤⣤⣤⠾⠃⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀