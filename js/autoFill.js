function searchAccounts(accountName) {
  // Assuming you have a server-side script (e.g., search_accounts.php) to fetch account details
  // You might need to adjust the URL and parameters based on your server-side implementation
  let url = `../php/fillAccount.php?accountName=${encodeURIComponent(
    accountName
  )}`;

  // Using AJAX to fetch account details
  fetch(url)
    .then((response) => response.json())
    .then((data) => displayAccountSuggestions(data))
    .catch((error) => console.error("Error:", error));
}

document.body.addEventListener("click", function (event) {
  // Check if the click is outside the dropdown container or its children
  if (!event.target.closest(".dropdown-container")) {
    document.getElementById("accountList").style.display = "none";
  }
});

function displayAccountSuggestions(accounts) {
  let accountList = document.getElementById("accountList");
  accountList.innerHTML = "";

  accounts.forEach((account) => {
    let listItem = document.createElement("li");
    listItem.innerText = account.accName; // Assuming the account is a string
    listItem.addEventListener("click", function () {
      fillFormFields(account); // Pass the entire 'account' object
      accountList.style.display = "none"; // Hide the dropdown after clicking
    });
    accountList.appendChild(listItem);
  });

  if (accounts.length > 0) {
    accountList.style.display = "block"; // Show the dropdown if there are suggestions
  } else {
    accountList.style.display = "none"; // Hide the dropdown if there are no suggestions
  }
}

function fillFormFields(account) {
  document.getElementById("accountName").value = account.accName;
  document.getElementById("segment").value = account.segment;
  document.getElementById("accountCategory").value = account.accCat;
  document.getElementById("address").value = account.address;
  document.getElementById("contactPerson").value = account.contactPerson;
  document.getElementById("contactNumber").value = account.contactNumber;
  document.getElementById("decisionMaker").value = account.decisionMaker;
  document.getElementById("dmDesignation").value = account.dmDesignation;
  document.getElementById("industry").value = account.industry;
  document.getElementById("accountSource").value = account.accSource;
  document.getElementById("area").value = account.area;
  document.getElementById("designation").value = account.designation;
  document.getElementById("emailAddress").value = account.email;
  document.getElementById("dmContactNumber").value = account.dmNumber;
  document.getElementById("existingSystem").value = account.existingSystem;
}