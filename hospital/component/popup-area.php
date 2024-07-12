<div class="popup" id="areaPopup">
    <div class="popup-header">
        <h2>エリアをご選択ください。<span class="badge bg-success">任意</span></h2>
        <span class="popup-close">✖</span>
    </div>
    <div class="popup-selection-content">
        <h2>地方</h2>
        <?php
            if (!isset($areaData)) {
                $areaData = [];
            }

            foreach ($areaData as $key => $value1) {
                echo '<div class="form-group"><label><input type="checkbox" data-value="'.$key.'">'.$key.'</label><select class="area-selection" multiple="multiple">';
                foreach ($value1 as $value2) {
                    echo '<option value="'.$value2['id'].'">'.$value2['pref_name'].'</option>';
                }
                echo '</select></div>';
            }
        ?>
    </div>
    <div class="popup-footer previous-footer">
        <div>
            <button class="open-previous-popup">戻る</button>
        </div>
        <div>
            <button class="clear-data bg-warning">クリア</button>
            <button class="open-next-popup">次へ</button>
        </div>
    </div>
</div>