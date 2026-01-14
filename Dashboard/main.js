function toggleDropdown() {
    const menu = document.getElementById("dropdownMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// Close dropdown when clicking outside
document.addEventListener("click", function(e) {
    const userMenu = document.querySelector(".user-menu");
    const dropdown = document.getElementById("dropdownMenu");

    if (!userMenu.contains(e.target)) {
        dropdown.style.display = "none";
    }
});

const adminInput = document.getElementById('adminSearch');
const resultsBox = document.getElementById('adminResults');

adminInput.addEventListener('input', function() {
    const query = this.value.trim();
    if(query.length < 1) {
        resultsBox.innerHTML = '';
        return;
    }

    fetch('search_proprietaires.php?q=' + encodeURIComponent(query))
        .then(res => res.json())
        .then(data => {
            resultsBox.innerHTML = ''; // clear previous results
            data.forEach(admin => {
                const div = document.createElement('div');
                div.classList.add('result-item');
                
                // Name on top
                const name = document.createElement('div');
                name.textContent = admin.prenom + ' ' + admin.nom;
                div.appendChild(name);
                
                // CIN below in gray
                const cin = document.createElement('div');
                cin.textContent = admin.cin;
                cin.style.color = 'gray';
                cin.style.fontSize = '0.9em';
                div.appendChild(cin);

                resultsBox.appendChild(div);

                // Optional: click to fill input
                div.addEventListener('click', () => {
                    adminInput.value = admin.prenom + ' ' + admin.nom;
                    resultsBox.innerHTML = '';
                    document.querySelector("#proprietaireId").value = admin.id;
                });
            });
        });
});


const clientInput = document.getElementById('clientSearch');
const ClientResultsBox = document.getElementById('clientResults');

clientInput.addEventListener('input', function() {
    const query = this.value.trim();
    if(query.length < 1) {
        ClientResultsBox.innerHTML = '';
        return;
    }
console.log(query)
    fetch('search_client.php?q=' + encodeURIComponent(query))
        .then(res => res.json())
        .then(data => {
            ClientResultsBox.innerHTML = ''; // clear previous results
            data.forEach(client => {
                const div = document.createElement('div');
                div.classList.add('result-item');
                
                // Name on top
                const name = document.createElement('div');
                name.textContent = client.prenom + ' ' + client.nom;
                div.appendChild(name);
                
                // CIN below in gray
                const cin = document.createElement('div');
                cin.textContent = client.cin;
                cin.style.color = 'gray';
                cin.style.fontSize = '0.9em';
                div.appendChild(cin);

                ClientResultsBox.appendChild(div);

                // Optional: click to fill input
                div.addEventListener('click', () => {
                    clientInput.value = client.prenom + ' ' + client.nom;
                    ClientResultsBox.innerHTML = '';
                    document.querySelector("#clientId").value = client.id;
                });
            });
        });
});