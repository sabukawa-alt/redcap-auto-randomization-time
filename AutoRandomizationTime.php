<?php
/**
 * External Module: Auto Randomization Time
 * ランダム化された日時を、指定したフィールドに自動保存するモジュールです。
 */

namespace JuntendoCRC\AutoRandomizationTime;

use ExternalModules\AbstractExternalModule;

class AutoRandomizationTime extends AbstractExternalModule
{
    /**
     * フォームが保存されるたびに呼ばれる処理
     */
    public function redcap_save_record($project_id, $record = null, $instrument, $event_id, $group_id = null, $survey_hash = null, $response_id = null, $repeat_instance = 1)
    {
        // 同じ保存処理の中で、ランダム化(自動トリガー含む)がまだ完了していない
        // 可能性があるため、実行タイミングを一旦遅らせる
        if ($this->delayModuleExecution()) {
            return;
        }

        // このプロジェクトのランダム化対象フィールド名を取得
        $targetField = $this->getRandomizationTargetField($project_id);
        if (empty($targetField)) {
            // ランダム化が設定されていないプロジェクトなら何もしない
            return;
        }

        // 設定画面の内容を取得
        $destField   = $this->getProjectSetting('rand-time-field');
        $dateFormat  = $this->getProjectSetting('rand-time-format');
        $destEventUniqueName = $this->getProjectSetting('rand-time-event');

        if (empty($destField)) {
            return; // 保存先フィールドが未設定
        }
        if (empty($dateFormat)) {
            $dateFormat = 'Y-m-d H:i:s'; // 未設定なら従来通りの形式
        }

        // 保存先イベントIDを決定する
        $destEventId = $event_id; // デフォルトは今回保存が行われたイベント
        if (!empty($destEventUniqueName)) {
            $eventNames = \REDCap::getEventNames(true, false); // [event_id => unique_event_name]
            $foundEventId = array_search($destEventUniqueName, $eventNames);
            if ($foundEventId !== false) {
                $destEventId = $foundEventId;
            }
        }

        // レコード全体のデータを取得（イベントをまたいでtargetFieldを探すため）
        $recordData = \REDCap::getData(array(
            'project_id'    => $project_id,
            'return_format' => 'array',
            'records'       => $record
        ));

        if (!isset($recordData[$record])) {
            return;
        }

        // ランダム化対象フィールドの値を、どのイベントでもいいので探す
        $isRandomized = false;
        foreach ($recordData[$record] as $evId => $evData) {
            if (!empty($evData[$targetField])) {
                $isRandomized = true;
                break;
            }
        }

        if (!$isRandomized) {
            return; // まだランダム化されていない
        }

        // 保存先フィールドの、保存先イベントでの現在値を確認
        $destValue = '';
        if (isset($recordData[$record][$destEventId][$destField])) {
            $destValue = $recordData[$record][$destEventId][$destField];
        }

        if ($destValue !== '') {
            return; // すでに値が入っているので上書きしない
        }

        // 保存先フィールドに、指定された形式で現在日時を書き込む
        $now = date($dateFormat);

        $saveResult = \REDCap::saveData(
            'array',
            array($record => array($destEventId => array($destField => $now)))
        );

        if (!empty($saveResult['errors'])) {
            \REDCap::logEvent(
                'Auto Randomization Time モジュール エラー',
                print_r($saveResult['errors'], true),
                '',
                $record,
                $destEventId
            );
        }
    }

    /**
     * このプロジェクトのランダム化対象フィールド名をデータベースから取得
     */
    private function getRandomizationTargetField($project_id)
    {
        $sql = "select target_field from redcap_randomization where project_id = ?";
        $q = $this->query($sql, array($project_id));
        if ($row = $q->fetch_assoc()) {
            return $row['target_field'];
        }
        return null;
    }
}