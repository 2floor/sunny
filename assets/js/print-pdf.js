let currentIndex = 0;
let pdfLinks = [];
let path = '../';

function showPdf(index) {
    $('.pdf-container').removeClass('active');
    $(`.pdf-container[data-index="${index}"]`).addClass('active');
}

$('#prevPDF').click(function() {
    if (currentIndex > 0) {
        currentIndex--;
        showPdf(currentIndex);
    }
});

$('#nextPDF').click(function() {
    if (currentIndex < pdfLinks.length - 1) {
        currentIndex++;
        showPdf(currentIndex);
    }
});

$('#downloadAllPDF').click(function() {
    pdfLinks.forEach(function(link) {
        let a = document.createElement('a');
        a.href = path + link;
        a.download = link.split('/').pop();
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    });
});

$('#closePDF').click(function() {
    $('.print-result').hide();
});

function handlePrintPDF (data, innerPath ='../') {
    currentIndex = 0;
    path = innerPath;
    $('.print-result').show();
    $('#pdf-render-container').empty();
    pdfLinks = data;

    pdfLinks.forEach(function(link, index) {
        $('#pdf-render-container').append(`
            <div class="pdf-container" data-index="${index}">
                <iframe src="${path}${link}"></iframe>
            </div>
        `);
    });

    showPdf(currentIndex);
}