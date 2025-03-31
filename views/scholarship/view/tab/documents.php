<div class="tab-pane fade <?= $tab == 'documents' ? 'show active': '' ?>" id="tab-documents" role="tabpanel">
    <?= $this->render('../documents', [
        'model' => $model,
        'pageLength' => 5
    ]) ?>
</div>