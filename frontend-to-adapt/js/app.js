
function toggleMenu() {

    const nav = document.querySelector('.nav-links');

    nav.classList.toggle('active');
}


function closePopup() {

    document.getElementById('installPopup').style.display = 'none';
}
// Object mapping religions to their respective communities
const communitiesByReligion = {
    "Hindu": ["Brahmin", "Rajput", "Kayastha", "Maratha", "Yadav", "Vaishya/Baniya", "Jaat", "Gurjar", "Arora", "Swarnkar", "Patidar/Patel", "Lohana", "Bhumihar", "Baidya", "Mahishya", "Kayastha(Bengali","Gond", "Kurmi", "Teli", "Mahato", "Beldar", "Rawani", "Reddy", "Kamma", "Nair","Menoki", "Iyer", "Iyengar", "Mudaliyar", "Chettiar", "Lingayat", "Vokkalinga", "Nadar", "Gounder", "Billava", "Naidu"],
    "Muslim": ["Sunni", "Shia", "Ansari", "Sheikh", "Khan", "Syed", "Mughal", "Qureshi", "Mansoori", "Salmani", "Saifi", "Rayeen", "Malik", "Memon", "Khoja", "Mappila", "Rowther", "Konakani Muslim", "Lebbai"],
    "Sikh": ["Jat Sikh", "Arora", "Khatri", "Ramgarhia", "Ahluwalia", "Saini", "Kamboj", "Mazhabi/Ravidasia", "Lubana"],
    "Christian": ["Roman Catholic", "Protestant", "Pentecostal", "Syrian Christian", "Syro-Malabar", "Syro-Malankara", "Malankara Orthodox", "Nadar", "Vellalar", "Dalit Christian", "Jacobite", "Mar Thoma", "Goan Catholic", " East Indian Catholic", "Mangalorean Catholic", "Methodist", "Baptist", "Anglo Indian"],
    "Jain": ["Digambar", "Shvetambar", "Oswal", "Agarwal Jain", "Khandelwal", "porwal", "Jaiswal", "Huamad", "Chaturtha", "Saitwal", "Bagherwal", "Shrimal"],
    "Buddhist": ["Mahayana", "Theravada", "Vajrayana", "Navayana", "Bhutia", "Lepcha", "Tamang", "Sherpa", "Monpa", "Balti", "Ladakhi", "Barua", "Chakma", "Mog"]
};

// Get references to the HTML select elements
const religionSelect = document.getElementById('religion-select');
const communitySelect = document.getElementById('community-select');

// Event listener to detect when user selects a religion
if (religionSelect && communitySelect) {
    religionSelect.addEventListener('change', function() {
        const selectedReligion = this.value;

        // Reset the community dropdown back to default
        communitySelect.innerHTML = '<option value="">Community</option>';

        // If a valid religion is selected, populate its specific communities
        if (selectedReligion && communitiesByReligion[selectedReligion]) {
            communitiesByReligion[selectedReligion].forEach(function(community) {
                // Create a new <option> element
                const option = document.createElement('option');
                option.value = community;
                option.textContent = community;
                // Append it to the community dropdown
                communitySelect.appendChild(option);
            });
        }
    });
}

// window.addEventListener("load", function () {

//     if (religionSelect.value !== "") {

//         const selectedReligion = religionSelect.value;

//         communitySelect.innerHTML =
//         '<option value="">Select Community</option>';

//         communitiesByReligion[selectedReligion]
//         .forEach(function(community){

//             const option =
//             document.createElement('option');

//             option.value = community;

//             option.textContent = community;

//             communitySelect.appendChild(option);

//         });

//     }

// });
window.addEventListener("load", function () {

    if (religionSelect && communitySelect) {

        const selectedReligion = religionSelect.value;

        communitySelect.innerHTML =
        '<option value="">Select Community</option>';

        if (
            selectedReligion &&
            communitiesByReligion[selectedReligion]
        ) {

            communitiesByReligion[selectedReligion]
            .forEach(function(community) {

                const option =
                document.createElement('option');

                option.value = community;

                option.textContent = community;

                // IMPORTANT
                if (community === savedCommunity) {

                    option.selected = true;
                }

                communitySelect.appendChild(option);

            });

        }

    }

});
option.selected = true;