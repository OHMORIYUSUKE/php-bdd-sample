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
            // 検索結果エリアから検索
            $searchResultsDiv = $this->driver->findElement(WebDriverBy::className('search-results'));
            $content = $searchResultsDiv->getText();
            Assert::assertStringContainsString($keyword, $content);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @Then 学習者が求める情報が表示されること
     */
    public function assertLearnerInformationIsDisplayed(): void
    {
        try {
            // 検索結果エリアが存在することを確認
            $searchResultsDiv = $this->driver->findElement(WebDriverBy::className('search-results'));
            Assert::assertNotNull($searchResultsDiv, '検索結果エリアが表示されていません');
            
            // 結果アイテムが存在することを確認
            $resultItems = $this->driver->findElements(WebDriverBy::className('result-item'));
            Assert::assertGreaterThan(0, count($resultItems), '検索結果が表示されていません');
        } catch (\Exception $e) {
            throw new \Exception('学習者に有用な情報が表示されていません: ' . $e->getMessage());
        }
    }

    /**
     * @Then 適切なメッセージが表示されること
     */
    public function assertAppropriateMessageIsDisplayed(): void
    {
        try {
            $errorMessageElement = $this->driver->findElement(WebDriverBy::className('no-results-message'));
            Assert::assertNotNull($errorMessageElement, '適切なエラーメッセージが表示されていません');
        } catch (\Exception $e) {
            throw new \Exception('適切なメッセージの確認でエラーが発生しました: ' . $e->getMessage());
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