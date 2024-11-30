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

function addFiltersToTable(table) {
    const thead = table.querySelector("thead");
    const tbody = table.querySelector("tbody");
    const filterRow = document.createElement("tr");
    thead.appendChild(filterRow);

    Array.from(thead.querySelectorAll("th")).forEach((th, columnIndex) => {
        const filterCell = document.createElement("td");
        if (th.classList.contains("filterable")) {
            const input = document.createElement("input");
            input.type = "text";
            input.placeholder = `Filtrer...`;
            input.addEventListener("input", () => {
                filterTable(table, columnIndex, input.value);
            });
            filterCell.appendChild(input);
        }
        filterCell.colSpan = th.colSpan
        filterRow.appendChild(filterCell);
    });
}
function filterTable(table, columnIndex, query) {
    const tbody = table.querySelector("tbody")
    const rows = Array.from(tbody.querySelectorAll("tr"))
    const lowerCaseQuery = query.toLowerCase()
    rows.forEach(row => {
        const cell = row.cells[columnIndex]
        if (cell) {
            const text = cell.textContent.trim().toLowerCase()
            row.style.display = text.includes(lowerCaseQuery) ? "" : "none"
        }
    })
}


document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll(".table-filterable").forEach((table) => {
        addFiltersToTable(table)
    })
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