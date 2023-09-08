

const swalWithDefaultButtons = Swal.mixin({
    buttonsStyling: true, // You can remove this line, as true is the default value
  });


let ECABINET = {

    _formSaveSingleKey: 113, // F2
    _formSaveGroupKey: 83, // S

    loading: (bool, text) => {
        let status = bool === true ? 'show' : 'hide';
        $.LoadingOverlay(status, { text: text, textResizeFactor: 0.3, size: 100 });
    },

    
    validate: (wrapperElement) => {
        let valid = true;
        $(wrapperElement).find('input, select, textarea,number,time').each(function () {
            /**
             * Validate if element has required attribute and no value/input given
             */
            if ($(this).attr('required') !== undefined && $(this).val() === "") {
                valid = false;
                $(this).addClass('is-invalid');
                if ($(this).next().hasClass('select2')) {
                    $(this).next().addClass('is-invalid');
                }
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        return valid;
    },
    fetchMpById: (item) => {
        let mpId = item.value;
        let url = '/admin/fetch-mp-detail';
        if (mpId != '') {
            $.ajax({
                type: 'GET',
                url: url,
                data: { mpId: mpId },
                success: function (response) {
                    if (response.message === 'success') {
                        $('#full_name').val(response.user.name);
                        $('#email').val(response.user.email);
                    }
                },
                error: function (error) { }
            });
        }
    },
    fetchMinistryEmployeeById: (item) => {
        let ministry_employee_id = item.value;
        let url = '/admin/fetch-ministry-employee-detail';
        if (ministry_employee_id != '') {
            $.ajax({
                type: 'GET',
                url: url,
                data: { ministry_employee_id: ministry_employee_id },
                success: function (response) {
                    if (response.message === 'success') {
                        $('#full_name').val(response.user.full_name);
                        $('#email').val(response.user.email);
                    }
                },
            });
        }
    },
    // Agenda Approval
    confirmation: (id, element, userObjectJSON) => {
        let route, custom_title, custom_body;
        const userObject = JSON.parse(userObjectJSON);
        const userArray = Object.keys(userObject).map(userId => ({
          id: userId,
          name: userObject[userId].name,
          post_name: userObject[userId].post_name,
        }));
      
        if (element.id == 'approveAgenda') {
          route = `agenda-approve/${id}`;
          custom_title = "स्वीकृत";
          custom_body = "स्वीकृत गरिसकेको";
        } else if (element.id == 'submit-agenda') {
          route = `agenda-submit/${id}`;
          custom_title = "पेश";
          custom_body = "पेश गरिसकेको";
        } else if (element.id == 'approveAgenda-submit') {
          route = `agenda-approve/${id}`;
          custom_title = "पेश";
          custom_body = "पेश गरिसकेको";
        }
        
        const checkboxesHTML = userArray.map((user, index) => `
        <div style="text-align:left;">
        <label style="margin-left: 20px;  cursor: pointer; color:blue">
          <input type="radio" name="selectedUsers[]" value="${user.id}" ${index === 0 ? 'checked' : ''}>
          ${user.post_name ? user.post_name + ' - ' : ''} ${user.name}
        </label></div>
        `).join('');
        const swalOptions = {
          title: `${custom_title} गर्ने हो?`,
          html: `
            <div>
              <p>${custom_body} खण्डमा त्यसलाई पुन: सम्पादन गर्न सकिने छैन</p>
              ${checkboxesHTML}
            </div>
          `,
          showCancelButton: true,
          confirmButtonText: 'हो',
          cancelButtonText: 'होइन',
          reverseButtons: true,
        };

          const confirmSwal = Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-success mx-2',
              cancelButton: 'btn btn-danger mx-2',
              title: 'btn btn-success',
            },
            buttonsStyling: false,
          });
      
            confirmSwal.fire(swalOptions).then((result) => {
              if (result.isConfirmed) {
                const selectedUsers = Array.from(document.querySelectorAll('input[name="selectedUsers[]"]:checked'))
                .map(checkbox => checkbox.value);
                if (selectedUsers.length === 0) {
                  // Display a validation error
                  Swal.fire({
                    icon: 'error',
                    title: 'कृपया एकजना प्रयोगकर्ता छानुहोस',
                  });
                  return; // Exit the function, preventing further processing
                }else if(selectedUsers.length > 1){
                  Swal.fire({
                    icon: 'error',
                    title: 'कृपया एकजना मात्र प्रयोगकर्ता छानुहोस',
                  });
                  return;
                }
                $.ajax({
                  url: route,
                  type: 'POST',
                  data: { selectedUsers: selectedUsers },
                  success: function (result) {
                    if (result.status == 'success') {
                      const successSwalOptions = {
                        title: `${custom_body}`,
                        text: `यो प्रस्ताव सफलतापुर्बक ${custom_title} भएको छ`,
                        icon: "success",
                        timer: 10000,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                      };
                      Swal.fire(successSwalOptions);
                      location.reload();
                    }
                    if (result.status == 'failed') {
                      const listItems = result.message.map((error) => `<li>${error}</li>`).join('');
                      const errorSwalOptions = {
                        title: `Error`,
                        html: `<ol>${listItems}</ol>`,
                        icon: "error",
                        timer: 10000,
                        showCloseButton: true,
                      };
                      Swal.fire(errorSwalOptions);
                    }
                  },
                });
              }
            });
    },
    // Agenda Rejection
    confirmationRejection: (id, element, userObjectJSON) => {
        let route, custom_title, custom_body;
        const userObject = JSON.parse(userObjectJSON);
        console.log(userObject)
        const userArray = Object.keys(userObject).map(rejectionUserIds => ({
          id: rejectionUserIds,
          name: userObject[rejectionUserIds].name,
          post_name: userObject[rejectionUserIds].post_name,
        }));
      
        if (element.id == 'rejectAgenda') {
          route = `agenda-reject/${id}`;
          custom_title = "फिर्ता";
          custom_body = "फिर्ता गरिसकेको";
        } else if (element.id == 'submit-agenda') {
          route = `agenda-submit/${id}`;
          custom_title = "पेश";
          custom_body = "पेश गरिसकेको";
        } else if (element.id == 'approveAgenda-submit') {
          route = `agenda-approve/${id}`;
          custom_title = "पेश";
          custom_body = "पेश गरिसकेको";
        }

        const checkboxesHTML = userArray.map((user, index) => `
        <div style="text-align:left;">
          <label style="margin-left: 20px;  cursor: pointer; color:blue">
            <input type="radio" name="selectedUsers[]" value="${user.id}" ${index === 0 ? 'checked' : ''}>
            ${user.post_name ? user.post_name + ' - ' : ''} ${user.name}
          </label></div>
        `).join('');
         
        const swalOptions = {
          title: `${custom_title} गर्ने हो?`,
          html: `
            <div>
              <p>${custom_body} खण्डमा त्यसलाई पुन: सम्पादन गर्न सकिने छैन</p>
              ${checkboxesHTML}
            </div>
          `,
          showCancelButton: true,
          confirmButtonText: 'हो',
          cancelButtonText: 'होइन',
          reverseButtons: true,
          input: 'textarea',
          inputLabel: 'फिर्ता गर्नुको कारण लेख्नुहोस',
          inputPlaceholder: 'Type your message here...',
          inputAttributes: {
            'aria-label': 'Type your message here'
          },
          preConfirm: (reason) => {
            if (!reason) {
              Swal.showValidationMessage('कृपया फिर्ता गर्नुको कारण अनिवार्य गरिएको छ');
            }
            return reason;
          }
        };

          const confirmSwal = Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-success mx-2',
              cancelButton: 'btn btn-danger mx-2',
              inputLabel: 'btn btn-info',
              title: 'btn btn-danger',
            },
            buttonsStyling: false,
          });
      
            confirmSwal.fire(swalOptions).then((result) => {
              if (result.isConfirmed) {
                const selectedUsers = Array.from(document.querySelectorAll('input[name="selectedUsers[]"]:checked'))
                .map(checkbox => checkbox.value);
                // if (selectedUsers.length === 0) {

                  // Display a validation error
                  // Swal.fire({
                  //   showCancelButton: true,
                  //   confirmButtonText: 'हो',
                  //   cancelButtonText: 'होइन',
                  //   reverseButtons: true,
                  //   text: 'कृपया एकजना प्रयोगकर्ता छानुहोस',
                  // }).then((res) =>{
                  //   if(!res.isConfirmed){
                  //     return
                  //   }
                  // });
                  // Swal.fire({
                  //   text: "कुनै पनि प्रयोगकर्ता नछानेको हुदा सो प्रस्ताब पठाउने प्रयोगकर्ता लाइ फिर्ता गर्दै छौ!",
                  //   showCancelButton: true,
                  //   confirmButtonColor: '#3085d6',
                  //   cancelButtonColor: '#d33',
                  //   cancelButtonText: 'अन्य प्रयोगकर्ता लाइ पठाउन चाहन्छु',
                  //   confirmButtonText: 'हुन्छ'
                  // }).then((res) => {
                  //   debugger
                  //   // if (!res.isConfirmed) {
                  //   //   return
                  //   // }
                  // })
                // }else if(selectedUsers.length > 1){
                //   Swal.fire({
                //     icon: 'error',
                //     title: 'कृपया एकजना मात्र प्रयोगकर्ता छानुहोस',
                //   });
                //   return;
                // }
                const reason = result.value;
                if (reason.length === 0) {
                  // Display a validation error
                  Swal.fire({
                    icon: 'error',
                    title: 'कृपया फिर्ता गर्नुको कारण लेख्नुहोस',
                  });
                  return; // Exit the function, preventing further processing
                }
                $.ajax({
                  url: route,
                  type: 'POST',
                  data: { selectedUsers: selectedUsers , AgendaId: id, remarks: reason},
                  success: function (result) {
                    if (result.status == 'success') {
                      const successSwalOptions = {
                        title: `${custom_body}`,
                        text: `यो प्रस्ताव सफलतापुर्बक ${custom_title} भएको छ`,
                        icon: "success",
                        timer: 10000,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                      };
                      Swal.fire(successSwalOptions);
                      location.reload();
                    }
                    if (result.status == 'failed') {
                      const listItems = result.message.map((error) => `<li>${error}</li>`).join('');
                      const errorSwalOptions = {
                        title: `Error`,
                        html: `<ol>${listItems}</ol>`,
                        icon: "error",
                        timer: 10000,
                        showCloseButton: true,
                      };
                      Swal.fire(errorSwalOptions);
                    }
                  },
                });
              }
            });
    },
      
    // Meeting Request
    confirmationMeetingRequest: (id, element) => {
        let route, custom_title, custom_body;

        if(element.id == 'submit-meeting-request'){
            route = `submit-meeting-request/${id}`;
            custom_title = "पेश";
            custom_body = "पेश गरिसकेको";
        }
        if(element.id == 'meetingRequestapprove'){
            route = `meeting-request-approve/${id}`;
            custom_title = "स्वीकृत";
            custom_body = "स्वीकृत गरिसकेको";
        }else if(element.id == 'meetingRequestapprove-submit'){
            route = `meeting-request-approve/${id}`;
            custom_title = "पेश";
            custom_body = "पेश गरिसकेको";
        }
        swalWithDefaultButtons.fire({
            title: `${custom_title} गर्ने हो?`,
            text: `एक पटक बैठक आहवान   ${custom_body} खण्डमा त्यसलाई पुन: सम्पादन गर्न सकिने छैन`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'हो',
            cancelButtonText: 'होइन',
            reverseButtons: true,
          }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route,
                    type: 'POST',
                    success: function (result) {
                        if (result.status == 'success') {
                            Swal.fire({
                                title: `${custom_body}`,
                                text: `यो बैठक आहवान  सफलतापुर्बक ${custom_title} भएको छ`,
                                icon: "success",
                                timer: 10000,
                                buttons: false,
                            });
                            location.reload();
                        }
                    },
                });
            } 
          });
    },
    // Meeting Minute
    confirmationMeetingMinute: (id, element) => {
        let route, custom_title, custom_body;

        if(element.id == 'submit-meeting-minute'){
            route = `submit-meeting-minute/${id}`;
            custom_title = "पेश";
            custom_body = "पेश गरिसकेको";
        }
        if(element.id == 'approvemeetingMinute'){
            route = `meeting-minute-approve/${id}`;
            custom_title = "स्वीकृत";
            custom_body = "स्वीकृत गरिसकेको";
        }else if(element.id == 'approvemeetingMinute-submit'){
            route = `meeting-minute-approve/${id}`;
            custom_title = "पेश";
            custom_body = "पेश गरिसकेको";
        }
        swalWithDefaultButtons.fire({
            title: `${custom_title} गर्ने हो?`,
            text: `एक पटक बैठक माइनुट  ${custom_body} खण्डमा त्यसलाई पुन: सम्पादन गर्न सकिने छैन`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'हो',
            cancelButtonText: 'होइन',
            reverseButtons: true,
          }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route,
                    type: 'POST',
                    success: function (result) {
                        if (result.status == 'success') {
                            Swal.fire({
                                title: `${custom_body}`,
                                text: `यो बैठक माइनुट सफलतापुर्बक ${custom_title} भएको छ`,
                                icon: "success",
                                timer: 10000,
                                buttons: false,
                            });
                            location.reload();
                        }else if(result.status == 'failed'){
                            const listItems = result.message.map((error) => `<li>${error}</li>`).join('');
                            Swal.fire({
                                title: `Error`,
                                html: `<ol>${listItems}</ol>`,
                                icon: "error",
                                timer: 10000,
                                showCloseButton: true,
                            });
                            
                        }
                    },
                });
            } 
          });
        
    },
}

window.ECABINET = ECABINET;
