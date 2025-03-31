import {database} from './config.js';
import { set, ref } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-database.js";

$('#tech-issue-form').on('beforeSubmit', function(e) {
  e.preventDefault();

  const form = $(this);

  $.ajax({
    url: form.attr('action'),
    data: form.serialize(),
    method: 'post',
    dataType: 'json',
    success: (s) => {
      if (s.status == 'success') {
        set(ref(database, 'tech-issue/' + s.model.token), (new Date()).getTime())
        .then((result) => {
          swal.fire('Success', s.message, 'success');
          location.href = s.viewUrl;
          KTApp.unblockPage();
        })
        .catch((error) => {
          KTApp.unblockPage();
          console.error("Error setting data:", error);
        });
      }
      else {
        alert(s.message);
      }
    },
    error: (e) => {
      alert(e.responseText)
    }
  })
  return false;
})