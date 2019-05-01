<main class="container-fluid">
    <br />

    <h2>API RAJA ONGKIR</h2>

    <hr />

    <form action="<?= site_url(); ?>rajaongkir/cost" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Provinsi Asal</label>
                    <select name="provinsi" class="form-control" id="provinsi_asal">
                        <option value="">--- Pilih Provinsi Asal ---</option>
                        <?php foreach ($provinsi as $row) : ?>
                            <option value="<?= $row['province_id'] ?>"><?= $row['province']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Kota Asal</label>
                    <select name="kota_asal" class="form-control" id="kota_asal">
                        <option value="">--- Pilih Kota Asal ---</option>
                        <?php foreach ($city as $row) : ?>
                            <option value="<?= $row['city_id'] ?>"><?= $row['city_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <hr />
                <div class="form-group">
                    <div class="form-group">
                        <label>Select Provinsi Tujuan</label>
                        <select name="provinsi" class="form-control" id="provinsi_tujuan">
                            <option value="">--- Pilih Provinsi Tujuan ---</option>
                            <?php foreach ($provinsi as $row) : ?>
                                <option value="<?= $row['province_id'] ?>"><?= $row['province']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <label>Select Kota Tujuan</label>
                    <select name="kota_tujuan" class="form-control" id="kota_tujuan">
                        <option value="">--- Pilih Kota Tujuan ---</option>
                        <?php foreach ($city as $row) : ?>
                            <option value="<?= $row['city_id'] ?>"><?= $row['city_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Kurir</label>
                    <select name="courier" class="form-control" id="courier">
                        <option value="">--- Pilih Kurir ---</option>
                        <option value="jne">JNE</option>
                        <option value="pos">POS</option>
                        <option value="tiki">TIKI</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Berat</label>
                    <input type="number" id="weight" name="weight" class="form-control" placeholder="Masukkan Berat Barang">
                </div>
                <hr />
                <div class="form-group">
                    <button type="submit" id="check" class="btn btn-primary">Cek Ongkir</button>
                </div>
                <hr />
            </div>
        </div>
    </form>

    <hr />

    <section id="section-ongkir">
        <table id="mytable" class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Description</th>
                    <th>Biaya</th>
                    <th>Estimasi</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody id="table-ongkir">

            </tbody>
        </table>
    </section>

    <br />
    <br />
    <br />
</main>

<script>
    $(function() {
        $('#provinsi_asal').change(function() {
            const prov = $('#provinsi_asal').val();
            $.ajax({
                type: 'GET',
                url: '<?= site_url(); ?>rajaongkir/get_city_by_province/' + prov,
                dataType: 'json',
                success: function(res) {
                    let options = '';
                    for (let row of res) {
                        options += `<option value="` + row.city_id + `">` + row.city_name +
                            `</option>`;
                    }
                    $("#kota_asal").html(options);
                }
            });
        });

        $('#provinsi_tujuan').change(function() {
            const prov = $('#provinsi_tujuan').val();
            $.ajax({
                type: 'GET',
                url: '<?= site_url(); ?>rajaongkir/get_city_by_province/' + prov,
                dataType: 'json',
                success: function(res) {
                    let options = '';
                    for (let row of res) {
                        options += `<option value="` + row.city_id + `">` + row.city_name +
                            `</option>`;
                    }
                    $("#kota_tujuan").html(options);
                }
            });
        });

        $("#check").click(function(e) {
            e.preventDefault();
            $(this).html('Checking...');
            $(this).attr('disabled', true);
            const kota_asal = $('#kota_asal').val();
            const kota_tujuan = $('#kota_tujuan').val();
            const courier = $('#courier').val();
            const weight = $('#weight').val();
            const data = {
                kota_asal: kota_asal,
                kota_tujuan: kota_tujuan,
                courier: courier,
                weight: weight
            };

            $.ajax({
                type: 'POST',
                url: '<?= site_url(); ?>rajaongkir/cost',
                data: data,
                success: function(res) {
                    let html = '';
                    $.each(res, (index, element) => {
                        html += `<tr>`;
                        html += `<td>${element.service || '-'}</td>`;
                        html += `<td>${element.description || '-'}</td>`;
                        $.each(element.cost, (index, element) => {
                            html += `<td>${element.value || '-'}</td>`;
                            html += `<td>${element.etd || '-'} Hari</td>`;
                            html += `<td>${element.notes || '-'}</td>`;
                        });
                        html += `</tr>`;
                    });
                    $('#check').html('Cek Ongkir');
                    $('#check').attr('disabled', false);
                    $("#table-ongkir").html(html);
                }
            });
        });
    });
</script>