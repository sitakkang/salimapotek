<input type="hidden" id="edit_id" name="id" value="<?= $row->id_table ?>">
<div class="form-group">
    <label>Nomor Meja <span class="text-danger">*</span></label>
    <input type="text" id="edit_no_meja" name="no_meja" class="form-control"
           value="<?= htmlspecialchars($row->no_meja) ?>" maxlength="10" autocomplete="off">
</div>
<div class="form-group">
    <label>Kapasitas (orang) <span class="text-danger">*</span></label>
    <input type="number" id="edit_kapasitas" name="kapasitas" class="form-control"
           value="<?= $row->kapasitas ?>" min="1" max="100" autocomplete="off">
</div>
<div class="form-group">
    <label>Status</label>
    <select id="edit_status" name="status" class="form-control">
        <option value="AVAILABLE" <?= $row->status === 'AVAILABLE' ? 'selected' : '' ?>>Tersedia</option>
        <option value="OCCUPIED"  <?= $row->status === 'OCCUPIED'  ? 'selected' : '' ?>>Terpakai</option>
        <option value="RESERVED"  <?= $row->status === 'RESERVED'  ? 'selected' : '' ?>>Dipesan</option>
    </select>
</div>
