# Auto Randomization Time

ランダム化が行われた日時を、フォーム上のフィールドに自動的に保存するREDCap外部モジュールです。
手動の「Randomize」ボタンだけでなく、**自動（リアルタイム）ランダム化**にも対応しています。

## 機能
- レコードがランダム化された日時を自動的に取得
- 手動の「Randomize」ボタン、自動（リアルタイムトリガー）ランダム化の両方に対応
- 保存する日時の形式を選択可能（秒あり／秒なし／日付のみ）
- 保存先フィールドのイベントを指定可能

## インストール方法
1. このリポジトリを `<redcapのインストール先>/modules/auto_randomization_time_v1.0.0` というフォルダ名で配置
2. REDCapの **Control Center → External Modules** を開き、「Auto Randomization Time」を有効化
3. 対象プロジェクトの **Applications → External Modules** でも同様に有効化
4. モジュールの設定を行う：
   - **保存先フィールド**：ランダム化日時を保存したいテキストフィールド
   - **日時の形式**：フィールドのText Validationの設定と合わせる
   - **イベント**（縦断的プロジェクトのみ）：保存先フィールドがあるイベントを選択

## 注意事項
- プロジェクトでREDCap標準の「Randomization」機能が有効になっている必要があります
- 保存先フィールドには、@DEFAULT・@SETVALUE・@CALCTEXTなど**他のAction Tagを付けないでください**（本モジュールの保存処理と競合する可能性があります）
- データの更新がされないように、＠READONLYまたは＠HIDDENを設定することをお勧めします

## 作者
- Shoco & Aco & Coji

## ライセンス
MIT
