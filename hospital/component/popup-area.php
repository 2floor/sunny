<div class="popup" id="areaPopup">
    <div class="popup-header">
        <h2>エリアをご選択ください。<span class="badge bg-info">任意</span></h2>
        <span class="popup-close">✖</span>
    </div>
    <div class="popup-selection-content">
        <h2>地方</h2>
        <?php
            if (!isset($areaData)) {
                $areaData = [];
            }

            foreach ($areaData as $key => $value1) {
                echo '<div class="form-group"><div class="checkbox-label m-b-5"><input type="checkbox" id="areaSelect'.$key.'" data-value="'.$key.'"><label for="areaSelect'.$key.'">'.$key.'</label></div><select class="area-selection" multiple="multiple">';
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
            <button class="clear-data">クリア</button>
            <button class="open-next-popup">次へ</button>
        </div>
    </div>
</div>