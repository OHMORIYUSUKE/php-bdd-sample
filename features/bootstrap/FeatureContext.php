<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Assert;

class FeatureContext extends MinkContext implements Context
{
    private ?RemoteWebDriver $driver = null;

    /**
     * @Given ウェブサイトのトップページを開く
     */
    public function openHomePage(): void
    {
        try {
            $host = getenv('SELENIUM_HOST') ?: 'localhost';
            $port = getenv('SELENIUM_PORT') ?: '4444';
            $serverUrl = "http://{$host}:{$port}";
            $this->driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::chrome());
            $this->driver->get('http://app:80');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @When :keyword というキーワードで検索する
     */
    public function searchWithKeyword(string $keyword): void
    {
        try {
            $searchInput = $this->driver->findElement(WebDriverBy::name('query'));
            $searchInput->sendKeys($keyword);
            $searchInput->submit();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @Then 検索結果に :keyword が含まれていること
     */
    public function assertSearchResultsContain(string $keyword): void
    {
        try {
            $content = $this->driver->findElement(WebDriverBy::tagName('body'))->getText();
            Assert::assertStringContainsString($keyword, $content);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @AfterScenario
     */
    public function tearDown(): void
    {
        if ($this->driver) {
            $this->driver->quit();
        }
    }
}