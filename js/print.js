function printTicket(ticketNo, email){
    console.log(email);
    const sections = document.querySelectorAll('section');
    const allDivs = document.querySelectorAll('div');

    const printEmail = document.getElementById('printemail');
    const printDate = document.getElementById('printdate');
    const printFrom = document.getElementById('printfrom');
    const printTo = document.getElementById('printto');
    const printCost = document.getElementById('printcost');
    const printStatus = document.getElementById('printstatus');

    var date = document.getElementById("Date" + ticketNo).textContent;
    var from = document.getElementById("From" + ticketNo).textContent;
    var to = document.getElementById("To" + ticketNo).textContent;
    var leaves = document.getElementById("Leaves" + ticketNo).textContent;
    var arrives = document.getElementById("Arrives" + ticketNo).textContent;
    var cost = document.getElementById("Price" + ticketNo).textContent;
    var status = document.getElementById("Status" + ticketNo).textContent;
    //Adds ticket info
    printEmail.textContent = "Ticket for "+email;
    printDate.textContent = date;
    printFrom.textContent = "Travels From: "+from+" on "+leaves;
    printTo.textContent = "Travels To: "+to+" on "+arrives;
    printCost.textContent = "Total Cost: "+cost;
    printStatus.textContent = "Status: "+status;

    //Displays ticket
    allDivs.forEach(div => div.style.visibility = 'hidden');
    sections.forEach(section => section.style.display = 'none');
    document.getElementById('printsection').style.display = 'block';
    window.print();
    sections.forEach(section => section.style.display = 'none');
    allDivs.forEach(div => div.style.visibility = 'visible');
    document.getElementById('ticket').style.display = 'block';
}