# PHP BDD Sample Project

## 概要
このプロジェクトは、PHP（Docker）環境でBehat＋SeleniumによるE2Eテストを行う、BDD（振る舞い駆動開発）のデモサンプルです。

## 必要要件
- Docker
- Docker Compose

## 起動方法

### 1. アプリケーションとSeleniumの起動

```bash
docker-compose up -d --build
```

- アプリケーション: http://localhost:8000
- Selenium（VNC画面）: http://localhost:7900

### 2. Behatテストの実行

```bash
docker-compose run test
```

テスト結果が表示されます。

## 補足

- M1/M2 Macの場合は`seleniarm/standalone-chromium`イメージを利用しています。

## 停止方法

```bash
docker-compose down
```
