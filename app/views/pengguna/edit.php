<input type="hidden" id="edit_id" value="<?= $row->id_user ?>">
<input type="hidden" id="edit_username_old" value="<?= htmlspecialchars($row->username) ?>">

<div class="ds-form-group">
    <label>Nama Lengkap <span class="text-danger">*</span></label>
    <input type="text" id="edit_fullname" name="fullname" class="form-control"
           value="<?= htmlspecialchars($row->fullname) ?>" maxlength="200" autocomplete="off">
</div>

<div class="ds-form-group">
    <label>Username <span class="text-danger">*</span></label>
    <input type="text" id="edit_username" name="username" class="form-control"
           value="<?= htmlspecialchars($row->username) ?>" maxlength="100" autocomplete="off">
</div>

<div class="ds-form-group">
    <label>NIP</label>
    <input type="text" id="edit_nip" name="nip" class="form-control"
           value="<?= htmlspecialchars($row->nip) ?>" maxlength="50" autocomplete="off" placeholder="Nomor Induk Pegawai (opsional)">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Level <span class="text-danger">*</span></label>
            <select id="edit_level" name="level" class="form-control">
                <option value="">-- Pilih Level --</option>
                <?php foreach ($level as $l): ?>
                    <option value="<?= $l->id_level ?>" <?= $row->level == $l->id_level ? 'selected' : '' ?>>
                        <?= htmlspecialchars($l->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Status</label>
            <select id="edit_status" name="status" class="form-control">
                <option value="1" <?= $row->status == 1 ? 'selected' : '' ?>>Aktif</option>
                <option value="2" <?= $row->status == 2 ? 'selected' : '' ?>>Nonaktif</option>
            </select>
        </div>
    </div>
</div>
