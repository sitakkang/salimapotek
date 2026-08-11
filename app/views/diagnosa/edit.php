<input type="hidden" id="edit_id" name="id" value="<?= $row->id_diagnosa ?>">

<div class="ds-form-group">
    <label>Kode Diagnosa</label>
    <input type="text" id="edit_dgn_cat" name="dgn_cat" class="form-control"
           value="<?= htmlspecialchars($row->dgn_cat ?? '') ?>" maxlength="10" autocomplete="off">
</div>

<div class="ds-form-group">
    <label>Nama Diagnosa <span class="text-danger">*</span></label>
    <textarea id="edit_dgn_name" name="dgn_name" class="form-control" rows="3"
              maxlength="100"><?= htmlspecialchars($row->dgn_name ?? '') ?></textarea>
</div>
