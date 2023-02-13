document.addEventListener('DOMContentLoaded', function(e) {
  let testimonialForm = document.getElementById('mh-testimonial-form');

  testimonialForm.addEventListener('submit', (e) => {
    e.preventDefault();
    console.log('Prevent form to submit');
  });
});

