<?php

namespace Auction\Tests\Unit\Service;

use Auction\Model\Auction;
use \Auction\Dao\Auction as AuctionDao;
use Auction\Service\Closer;
use Auction\Service\EmailSender;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;



class CloserTest extends TestCase
{

    /**
     * @var MockObject
     */
    private AuctionDao $auctionDao;
    private Closer $closer;
    private Auction $auction1;
    private Auction $auction2;
    /**
     * @var MockObject
     */
    private EmailSender $emailSender;

    protected function setUp(): void
    {
        // Arrange
        $this->auction1 = new Auction('Camaro', new \DateTimeImmutable('8 days ago'));
        $this->auction2 = new Auction('Ferrari', new \DateTimeImmutable('10 days ago'));

        $this->auctionDao = $this->createMock(AuctionDao::class);
        // $this->auctionDao = $this->getMockBuilder(AuctionDao::class)->setConstructorArgs([new \PDO('sqlite::memory:')])->getMock();
        $this->auctionDao->method('retrieveNotFinished')->willReturn([$this->auction1, $this->auction2]);
        $this->auctionDao->method('retrieveFinished')->willReturn([$this->auction1, $this->auction2]);
        $this->auctionDao->expects($this->exactly(2))->method('update');

        $this->auctionDao->save($this->auction1);
        $this->auctionDao->save($this->auction2);

        $this->emailSender = $this->createMock(EmailSender::class);

        $this->closer  = new Closer($this->auctionDao, $this->emailSender);
    }
    public function testAuctionsLongerThanAWeekMustBeClosed(): void
    {
        // Act
        $this->closer->close();

        // Assert
        $auctions = [$this->auction1, $this->auction2];
        self::assertCount(2, $auctions);
        self::assertTrue($auctions[0]->isFinished());
        self::assertTrue($auctions[1]->isFinished());
    }

    public function testClosingTheAuctionMustTakePlaceEvenIfAnErrorOccursWhenSendingEmail(): void
    {
        $exception = new \Exception('Error sending email.');
        $this->emailSender->expects($this->exactly(2))->method('sendAuctionClosedEmail')->willThrowException($exception);
        $this->closer->close();
    }

    public function testClosingEmailMustBeSentIfAuctionIsEnded(): void
    {
        $this->emailSender->expects($this->exactly(2))->method('sendAuctionClosedEmail')->willReturnCallback(function (Auction $auction) {
            static::assertTrue($auction->isFinished());
        });
        $this->closer->close();
    }
}
