var addrPopup = null;

function deadend() {
   if (addrPopup && !addrPopup.closed) {	
      addrPopup.focus();
      return false;
   }
}

function checkModal() {
   if (addrPopup && !addrPopup.closed) {
      addrPopup.focus();
   }
}


