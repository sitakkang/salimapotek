<div class="ds-form-group">
    <label>Nama Lengkap <span class="text-danger">*</span></label>
    <input type="text" id="fullname" name="fullname" class="form-control"
           placeholder="Nama lengkap pengguna" maxlength="200" autocomplete="off">
</div>

<div class="ds-form-group">
    <label>Username <span class="text-danger">*</span></label>
    <input type="text" id="username" name="username" class="form-control"
           placeholder="Username untuk login" maxlength="100" autocomplete="off">
</div>

<div class="ds-form-group">
    <label>NIP</label>
    <input type="text" id="nip" name="nip" class="form-control"
           placeholder="Nomor Induk Pegawai (opsional)" maxlength="50" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Password <span class="text-danger">*</span></label>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="Min. 5 karakter" maxlength="100" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Ketik Ulang Password <span class="text-danger">*</span></label>
            <input type="password" id="passconf" name="passconf" class="form-control"
                   placeholder="Ulangi password" maxlength="100" autocomplete="off">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Level <span class="text-danger">*</span></label>
            <select id="level" name="level" class="form-control">
                <option value="">-- Pilih Level --</option>
                <?php foreach ($level as $l): ?>
                    <option value="<?= $l->id_level ?>"><?= htmlspecialchars($l->name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Status</label>
            <select id="status" name="status" class="form-control">
                <option value="1">Aktif</option>
                <option value="2">Nonaktif</option>
            </select>
        </div>
    </div>
</div>
