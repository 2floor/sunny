<div class="popup" id="cancerPopup">
    <div class="popup-header">
        <h2>ガン種類をご選択ください。 <span class="badge bg-danger">必須</span></h2>
        <span class="popup-close">✖</span>
    </div>
    <div class="popup-checkbox-content">
        <?php
            if (!isset($cancerData)) {
                $cancerData = [];
            }

            foreach ($cancerData as $value) {
                echo '<label><input type="checkbox" data-key="'.$value['id'].'" data-value="'.$value['cancer_type'].'">'.$value['cancer_type'].'</label>';
            }
        ?>
    </div>
    <div class="popup-footer next-footer">
        <div style="margin-right: 5px">
            <button class="clear-data bg-warning">クリア</button>
        </div>
        <div>
            <button class="open-next-popup">次へ</button>
        </div>
    </div>
</div>