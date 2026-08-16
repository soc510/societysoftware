<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
                <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Import Society Payments from PDF</h6>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary btn-sm" href="<?php echo $this->Html->url(array('controller' => 'societys', 'action' => 'add_society_payments')); ?>"><i class="fa fa-arrow-left" style="color:#fff;font-size:10px;"></i>&nbsp;Back to Payments</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Select PDF File</label>
                                        <input type="file" class="form-control" id="pdfFileInput" accept=".pdf">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">&nbsp;</label>
                                        <button type="button" class="btn btn-info btn-block" id="btnParsePdf" disabled><i class="fa fa-cog"></i>&nbsp;Parse PDF</button>
                                    </div>
                                </div>
                            </div>

                            <div id="pdfParsingStatus" style="display:none;">
                                <div class="alert alert-info">
                                    <i class="fa fa-spinner fa-spin"></i>&nbsp;<span id="statusText">Reading PDF...</span>
                                </div>
                            </div>

                            <div id="pdfPreviewSection" style="display:none;">
                                <hr>
                                <h6><strong>Parsed Payment Data</strong> (<span id="parsedRowCount">0</span> rows found)</h6>
                                <div class="alert alert-warning" id="parseWarnings" style="display:none;"></div>
                                <div class="table-responsive" style="max-height:500px;overflow-y:auto;">
                                    <table class="table table-bordered table-condensed table-hover" id="parsedDataTable">
                                        <thead style="background:#f5f5f5;">
                                            <tr>
                                                <th><input type="checkbox" id="selectAllRows" checked></th>
                                                <th>#</th>
                                                <th>Debit To</th>
                                                <th>Payment Date</th>
                                                <th>Payment Type</th>
                                                <th>Amount</th>
                                                <th>Tax Amount</th>
                                                <th>Particulars</th>
                                                <th>Cheque No</th>
                                                <th>Cheque Date</th>
                                                <th>Voucher No</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody id="parsedDataBody"></tbody>
                                    </table>
                                </div>
                                <br>
                                <form method="post" id="importPdfForm" action="<?php echo $this->Html->url(array('controller' => 'societys', 'action' => 'add_society_payment_from_pdf')); ?>">
                                    <input type="hidden" name="data[pdf_payments]" id="pdfPaymentsJson" value="">
                                    <button type="submit" class="btn btn-success" id="btnImportPayments" disabled>
                                        <i class="fa fa-check"></i>&nbsp;Import Selected Payments
                                    </button>
                                    <button type="button" class="btn btn-default" onclick="window.location.reload();">
                                        <i class="fa fa-refresh"></i>&nbsp;Reset
                                    </button>
                                </form>
                            </div>

                            <div id="debugSection" style="display:none;">
                                <hr>
                                <h6><strong>Debug: Raw PDF Text</strong></h6>
                                <textarea id="debugOutput" style="width:100%;height:300px;font-size:11px;font-family:monospace;"></textarea>
                            </div>

                            <div class="clearfix"></div>
                            <br>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h6 class="panel-title">PDF Import Instructions</h6>
                                </div>
                                <div class="panel-body">
                                    <p><strong>Steps to import payments from PDF:</strong></p>
                                    <ol>
                                        <li>Select the PDF file containing the payment register.</li>
                                        <li>Click <strong>"Parse PDF"</strong> to extract payment data.</li>
                                        <li>Review the parsed data in the table below.</li>
                                        <li>Uncheck any rows you don't want to import.</li>
                                        <li>Click <strong>"Import Selected Payments"</strong> to save.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $this->webroot; ?>js/pdf.min.js"></script>
<script>
var pdfjsLib = window['pdfjs-dist/build/pdf'];
pdfjsLib.GlobalWorkerOptions.workerSrc = '<?php echo $this->webroot; ?>js/pdf.worker.min.js';
var ledgerHeadsMap = <?php echo json_encode($societyExpenseLedgerHeadsLists); ?>;
var parsedPayments = [];

document.getElementById('pdfFileInput').addEventListener('change', function() {
    document.getElementById('btnParsePdf').disabled = !this.files.length;
    document.getElementById('pdfPreviewSection').style.display = 'none';
});

document.getElementById('btnParsePdf').addEventListener('click', function() {
    var fileInput = document.getElementById('pdfFileInput');
    if (!fileInput.files.length) return;
    document.getElementById('pdfParsingStatus').style.display = 'block';
    document.getElementById('statusText').textContent = 'Reading PDF file...';
    this.disabled = true;
    var reader = new FileReader();
    reader.onload = function(e) { parsePdf(new Uint8Array(e.target.result)); };
    reader.readAsArrayBuffer(fileInput.files[0]);
});

function parsePdf(data) {
    pdfjsLib.getDocument({data: data}).promise.then(function(pdf) {
        var total = pdf.numPages;
        document.getElementById('statusText').textContent = 'Parsing ' + total + ' page(s)...';
        var allItems = [];
        var chain = Promise.resolve();
        for (var i = 1; i <= total; i++) {
            (function(pn) {
                chain = chain.then(function() {
                    return pdf.getPage(pn).then(function(page) {
                        return page.getTextContent({normalizeWhitespace: true}).then(function(tc) {
                            allItems = allItems.concat(tc.items.map(function(it) {
                                return {text: it.str, x: Math.round(it.transform[4]), y: Math.round(it.transform[5]), page: pn};
                            }));
                            page.cleanup();
                        });
                    });
                });
            })(i);
        }
        chain.then(function() {
            var payments = extractPayments(allItems);
            displayParsedData(payments);
            document.getElementById('pdfParsingStatus').style.display = 'none';
            document.getElementById('btnParsePdf').disabled = false;
        }).catch(function(err) {
            document.getElementById('statusText').textContent = 'Error: ' + err.message;
            document.getElementById('btnParsePdf').disabled = false;
        });
    }).catch(function(err) {
        document.getElementById('statusText').textContent = 'Error: ' + err.message;
        document.getElementById('btnParsePdf').disabled = false;
    });
}

function extractPayments(allItems) {
    var filtered = allItems.filter(function(it) { return it.text.trim() !== ''; });

    var yGroups = {};
    filtered.forEach(function(it) {
        var yKey = Math.round(it.y / 4) * 4;
        if (!yGroups[yKey]) yGroups[yKey] = [];
        yGroups[yKey].push(it);
    });

    var lines = [];
    for (var yk in yGroups) {
        var items = yGroups[yk];
        items.sort(function(a, b) { return a.x - b.x; });

        var merged = [];
        var cur = {text: items[0].text, x: items[0].x};
        for (var i = 1; i < items.length; i++) {
            if (items[i].x - cur.x < 3) {
                cur.text += items[i].text;
            } else {
                if (cur.text.trim()) merged.push({text: cur.text.trim(), x: cur.x});
                cur = {text: items[i].text, x: items[i].x};
            }
        }
        if (cur.text.trim()) merged.push({text: cur.text.trim(), x: cur.x});

        lines.push({y: parseInt(yk), items: merged, page: items[0].page});
    }

    lines.sort(function(a, b) {
        if (a.page !== b.page) return a.page - b.page;
        return b.y - a.y;
    });

    // DEBUG: Show what pdf.js extracted
    var dbg = 'Total items: ' + filtered.length + ', Lines: ' + lines.length + '\n\n';
    for (var di = 0; di < lines.length; di++) {
        dbg += 'LINE ' + di + ' [page=' + lines[di].page + ' y=' + lines[di].y + ']: ';
        for (var dj = 0; dj < lines[di].items.length; dj++) {
            dbg += '[x=' + lines[di].items[dj].x + ' "' + lines[di].items[dj].text + '"] ';
        }
        dbg += '\n';
    }
    document.getElementById('debugOutput').value = dbg;
    document.getElementById('debugSection').style.display = 'block';

    var colBoundaries = detectColumnBoundaries(lines);
    dbg += '\nColumn Boundaries: ' + JSON.stringify(colBoundaries) + '\n';
    document.getElementById('debugOutput').value = dbg;

    if (!colBoundaries) return [];

    return parseDataRows(lines, colBoundaries);
}

function detectColumnBoundaries(lines) {
    for (var i = 0; i < lines.length; i++) {
        var fullText = lines[i].items.map(function(it) { return it.text; }).join('').toLowerCase().replace(/\s/g, '');
        if (fullText.indexOf('vno') > -1 || fullText.indexOf('vnno') > -1 || fullText.indexOf('srno') > -1) {
            var cols = {};
            lines[i].items.forEach(function(it) {
                var t = it.text.toLowerCase().replace(/[\s.]/g, '');
                if (/^v?no$|^vno$|^vnno$|^srno$/.test(t)) cols.vno = it.x;
                else if (/^v?date$|^date$/.test(t)) cols.date = it.x;
                else if (/^by$/.test(t)) cols.by = it.x;
                else if (/amount/i.test(t)) cols.amount = it.x;
                else if (/account|ledger|debit/i.test(t)) cols.accountName = it.x;
                else if (/^rs\.?$/.test(t)) cols.rs = it.x;
                else if (/particular/i.test(t)) cols.particulars = it.x;
                else if (/remark|note/i.test(t)) cols.remark = it.x;
            });

            if (i > 0) {
                lines[i - 1].items.forEach(function(it) {
                    var t = it.text.toLowerCase().replace(/[\s.]/g, '');
                    if (/cheque|chq/.test(t)) {
                        if (!cols.chequeNo) cols.chequeNo = it.x;
                        else if (it.x > cols.chequeNo + 20) cols.chequeDate = it.x;
                    }
                });
            }
            if (i + 1 < lines.length) {
                lines[i + 1].items.forEach(function(it) {
                    var t = it.text.toLowerCase().replace(/[\s.]/g, '');
                    if (/cheque|chq/.test(t)) {
                        if (!cols.chequeNo) cols.chequeNo = it.x;
                        else if (it.x > cols.chequeNo + 20) cols.chequeDate = it.x;
                    }
                    if (/^no$/.test(t) && !cols.chequeNo) cols.chequeNo = it.x;
                    if (/^date$/.test(t) && cols.chequeNo && !cols.chequeDate) cols.chequeDate = it.x;
                });
            }

            if (cols.vno !== undefined || cols.date !== undefined) {
                cols._headerIdx = i;
                return cols;
            }
        }
    }
    return null;
}

function parseDataRows(lines, cols) {
    var payments = [];
    var startIdx = (cols._headerIdx || 0) + 1;

    var checkNearby = {};
    for (var ci = startIdx - 3; ci <= startIdx + 1; ci++) {
        if (ci >= 0 && ci < lines.length) {
            lines[ci].items.forEach(function(it) {
                var t = it.text.toLowerCase().replace(/[\s.]/g, '');
                if (/cheque|chq/.test(t)) {
                    if (!checkNearby.chequeNo) checkNearby.chequeNo = it.x;
                    else if (it.x > checkNearby.chequeNo + 20) checkNearby.chequeDate = it.x;
                }
            });
        }
    }
    if (!cols.chequeNo && checkNearby.chequeNo) cols.chequeNo = checkNearby.chequeNo;
    if (!cols.chequeDate && checkNearby.chequeDate) cols.chequeDate = checkNearby.chequeDate;

    var colArr = [];
    for (var k in cols) {
        if (k === '_headerIdx') continue;
        colArr.push({name: k, x: cols[k]});
    }
    colArr.sort(function(a, b) { return a.x - b.x; });

    var pendingRow = null;
    var lastPage = null;

    for (var li = startIdx; li < lines.length; li++) {
        var line = lines[li];
        var lineText = line.items.map(function(it) { return it.text; }).join(' ').trim();
        var lineLower = lineText.toLowerCase();

        if (lineLower.indexOf('total') > -1 || lineLower.indexOf('rupees') > -1) continue;
        if (/^page\s+\d/i.test(lineText)) continue;
        if (lineLower.indexOf('payment register') > -1) continue;
        if (/^[\-=_\s]+$/.test(lineText)) continue;

        if (isNewHeaderRow(line.items)) {
            cols = detectColumnBoundaries(lines.slice(li - 1));
            if (cols) {
                startIdx = li + (cols._headerIdx || 0) + 1;
                li = startIdx - 1;
            }
            continue;
        }

        var mapped = mapToColumns(line.items, colArr);

        var hasVno = mapped.vno && /^\d+$/.test(mapped.vno.trim());
        var hasDate = mapped.date && /\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}/.test(mapped.date);

        if (hasVno && hasDate) {
            if (pendingRow) {
                var p = buildPayment(pendingRow);
                if (p) payments.push(p);
            }
            pendingRow = mapped;
            lastPage = line.page;
        } else if (pendingRow) {
            for (var mk in mapped) {
                if (mapped[mk] && mapped[mk].trim()) {
                    if (!pendingRow[mk] || !pendingRow[mk].trim()) {
                        pendingRow[mk] = mapped[mk];
                    } else {
                        pendingRow[mk] += ' ' + mapped[mk];
                    }
                }
            }
        }
    }

    if (pendingRow) {
        var p = buildPayment(pendingRow);
        if (p) payments.push(p);
    }

    return payments;
}

function isNewHeaderRow(items) {
    var t = items.map(function(it) { return it.text; }).join('').toLowerCase().replace(/\s/g, '');
    return (/vno/.test(t) || /vnno/.test(t) || /srno/.test(t)) && /date/.test(t);
}

function mapToColumns(items, colArr) {
    var d = {};
    items.forEach(function(it) {
        var best = null, minDist = 9999;
        for (var j = 0; j < colArr.length; j++) {
            var dist = Math.abs(it.x - colArr[j].x);
            if (dist < minDist) { minDist = dist; best = colArr[j].name; }
        }
        if (best && minDist < 120) {
            d[best] = (d[best] ? d[best] + '' : '') + it.text;
        }
    });
    return d;
}

function buildPayment(d) {
    var amt = cleanAmt(d.amount || d.rs || '');
    if (!amt || amt === '0.00') return null;

    var acName = clean(d.accountName || '');
    var particular = clean(d.particulars || d.remark || '');

    return {
        voucherNo: clean(d.vno),
        paymentDate: parseDate(d.date),
        paymentType: (clean(d.by || '').toLowerCase().indexOf('cash') > -1) ? 'Cash' : 'Bank',
        paymentBy: clean(d.by),
        amount: amt,
        taxAmount: '0',
        particulars: particular,
        chequeNo: clean(d.chequeNo),
        chequeDate: parseDate(d.chequeDate),
        accountName: acName,
        remark: clean(d.remark || '')
    };
}

function clean(s) { return (s || '').replace(/\s+/g, ' ').trim(); }
function cleanAmt(s) {
    var n = parseFloat((s || '').replace(/[^0-9.\-]/g, ''));
    return isNaN(n) || n <= 0 ? '0.00' : n.toFixed(2);
}
function parseDate(s) {
    if (!s) return '';
    s = s.replace(/\s+/g, '').trim();
    var m = s.match(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})/);
    if (m) {
        var dd = m[1], mm = m[2], yy = m[3];
        if (yy.length === 2) yy = '20' + yy;
        if (parseInt(mm) > 12) { var t = dd; dd = mm; mm = t; }
        return yy + '-' + mm.padStart(2, '0') + '-' + dd.padStart(2, '0');
    }
    return '';
}

function displayParsedData(payments) {
    parsedPayments = payments;
    var tbody = document.getElementById('parsedDataBody');
    tbody.innerHTML = '';
    document.getElementById('parsedRowCount').textContent = payments.length;

    if (!payments.length) {
        document.getElementById('pdfPreviewSection').style.display = 'block';
        document.getElementById('parseWarnings').style.display = 'block';
        document.getElementById('parseWarnings').innerHTML = '<strong>No payment rows found.</strong> Check PDF format.';
        document.getElementById('btnImportPayments').disabled = true;
        return;
    }

    var warns = [], html = '';
    for (var i = 0; i < payments.length; i++) {
        var p = payments[i];
        var debit = p.accountName || p.particulars;
        var ok = findLedger(debit);
        if (!ok) warns.push('Row ' + (i + 1) + ': "' + debit + '" not matched');
        html += '<tr><td><input type="checkbox" class="row-check" data-index="' + i + '" checked></td>'
            + '<td>' + (i + 1) + '</td>'
            + '<td>' + esc(debit) + (ok ? '' : ' <span class="label label-warning">Not matched</span>') + '</td>'
            + '<td>' + esc(p.paymentDate) + '</td>'
            + '<td>' + esc(p.paymentType) + '</td>'
            + '<td class="text-right">' + esc(p.amount) + '</td>'
            + '<td class="text-right">' + esc(p.taxAmount) + '</td>'
            + '<td>' + esc(p.particulars) + '</td>'
            + '<td>' + esc(p.chequeNo) + '</td>'
            + '<td>' + esc(p.chequeDate) + '</td>'
            + '<td>' + esc(p.voucherNo) + '</td>'
            + '<td>' + esc(p.remark) + '</td></tr>';
    }
    tbody.innerHTML = html;

    var wEl = document.getElementById('parseWarnings');
    if (warns.length) {
        wEl.style.display = 'block';
        wEl.innerHTML = '<strong>' + warns.length + ' row(s) unmatched Debit To.</strong><br><small>' + warns.slice(0, 5).join('<br>') + (warns.length > 5 ? '<br>...and ' + (warns.length - 5) + ' more' : '') + '</small>';
    } else { wEl.style.display = 'none'; }

    document.getElementById('pdfPreviewSection').style.display = 'block';
    document.getElementById('btnImportPayments').disabled = false;
    updateSelected();
}

function findLedger(name) {
    if (!name) return null;
    var nl = name.toLowerCase().trim();
    for (var id in ledgerHeadsMap) {
        if (ledgerHeadsMap[id].toLowerCase().trim() === nl) return id;
    }
    for (var id in ledgerHeadsMap) {
        var ll = ledgerHeadsMap[id].toLowerCase().trim();
        if (nl.indexOf(ll) > -1 || ll.indexOf(nl) > -1) return id;
    }
    return null;
}

function esc(t) {
    var d = document.createElement('div');
    d.appendChild(document.createTextNode(t || ''));
    return d.innerHTML;
}

document.getElementById('selectAllRows').addEventListener('change', function() {
    var ch = document.querySelectorAll('.row-check');
    for (var i = 0; i < ch.length; i++) ch[i].checked = this.checked;
    updateSelected();
});
document.getElementById('parsedDataBody').addEventListener('change', function(e) {
    if (e.target.classList.contains('row-check')) updateSelected();
});

function updateSelected() {
    var ch = document.querySelectorAll('.row-check:checked'), sel = [];
    for (var i = 0; i < ch.length; i++) sel.push(parsedPayments[parseInt(ch[i].getAttribute('data-index'))]);
    document.getElementById('pdfPaymentsJson').value = JSON.stringify(sel);
    document.getElementById('btnImportPayments').disabled = !sel.length;
}

document.getElementById('importPdfForm').addEventListener('submit', function(e) {
    var ch = document.querySelectorAll('.row-check:checked');
    if (!ch.length) { e.preventDefault(); alert('Select at least one row.'); return; }
    updateSelected();
    document.getElementById('btnImportPayments').disabled = true;
    document.getElementById('btnImportPayments').innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp;Importing...';
});
</script>
