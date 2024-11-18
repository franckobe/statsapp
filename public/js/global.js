function sortTable(table, columnIndex, asc = true) {
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    rows.sort((rowA, rowB) => {
        const cellA = rowA.cells[columnIndex].textContent.trim()
        const cellB = rowB.cells[columnIndex].textContent.trim()
        const isNumeric = !isNaN(cellA) && !isNaN(cellB)
        return isNumeric
            ? (asc ? cellA - cellB : cellB - cellA)
            : (asc ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA))
    });
    rows.forEach(row => tbody.appendChild(row))
}


document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll(".table-orderable").forEach((table) => {
        table.querySelectorAll("thead th").forEach((header, index) => {
            let ascending = true
            header.addEventListener("click", () => {
                sortTable(table, index, ascending)
                table.querySelectorAll("thead th").forEach((elem) => {
                    elem.setAttribute('data-order', '')
                })
                header.setAttribute('data-order', ascending ? 'ASC' : 'DESC')
                ascending = !ascending
            })
        })
    })
})