import ReactDOM from "react-dom";
import React, {useEffect, useState} from "react";
import http from "../providers/http.provider";

export const HomeOwner = () => {
    const [rooms, setRooms] = useState([]);
    const [selectedRoom, setSelectedRoom] = useState({});

    const [submitted, setSubmitted] = useState(false);
    const [loader, setLoader] = useState(false);

    useEffect(() => {
        getRoom()
    }, []);

    const getRoom = () => {
        //get-room-with-home-owners
        http.get('get-home-owners').then((data) => {
            setRooms(data.data)
        })
    }

    const getHomerOwner = (room) => {
        const tmp_pkg = setCheckedData(rooms, room);
        setRooms(tmp_pkg['allData']);
        //get-room-with-home-owners
        console.log(room)
        setSelectedRoom(room);
    }

    const findActive = (data) => {
        return data.find(x => x.checked);
    }

    const setCheckedData = (allData, data) => {
        const tmp_data = [...allData];
        const findActiveData = findActive(tmp_data);
        if (findActiveData) {
            const activeIndex = tmp_data.indexOf(findActiveData);
            tmp_data[activeIndex].checked = false;
        }

        let index = tmp_data.findIndex(p => p === data);
        tmp_data[index].checked = true;
        return {allData: tmp_data, index};
    }

    const setMultiSelect = (allData, data) => {
        const tmp_data = [...allData];

        let index = tmp_data.findIndex(p => p === data);
        tmp_data[index].checked = !data.checked;
        return {allData: tmp_data, index};
    }

    const setHomeData = (home) => {
        const tmp_data = [...rooms];
        const findActiveData = findActive(tmp_data);
        const activeIndex = tmp_data.indexOf(findActiveData);

        const tmp_pkg = setMultiSelect(findActiveData.home_owners, home);
        tmp_data[activeIndex].home_owners = tmp_pkg['allData'];
        console.log(tmp_data)
        setRooms(tmp_data);
    }

    const prepareData = () => {
        let temp_pkg = [...rooms];
        const data = [];
        for (let i = 0; i < temp_pkg.length; i++) {

            const activeRooms = temp_pkg[i].home_owners.filter(x => x.checked);

            for (const room of activeRooms) {
                const result = {};
                result.room_id = temp_pkg[i].id;
                result.home_owner_id = room.id;
                data.push(result);
            }
        }

        return data;
    }

    const saveHomeOwner = () => {
        const home_items = prepareData();
        setLoader(true);

        http.post('save-home-owners', {home_items, user_email: window.USER_EMAIL}).then((response) => {
            /* const config = {
                 timeout: 5000,
                 positionY: "top", // top or bottom
                 positionX: "right", // right left, center
                 distanceY: 120, // Integer value
                 distanceX: 5, // Integer value
                 zIndex: 100, // Integer value
                 theme: "default", // default, ligh or  dark (leave empty for "default" theme)
                 duplicates: false, // true or false - by default it's false
                 animations: true, // Show animations - by default it's true
             };
             let mytoast = new Toastme(config);
             mytoast.success(response.message);*/
            setSubmitted(true);


        })
    }

    return (
        <div className="container home-owner-cls">

            {!submitted && <div className="row">
                <div className="col-md-12">
                    <h3 className="title-un"><b>Begin your Smart Home journey by telling us about the Home Automation
                        features you are interested in...</b></h3>
                </div>

                <div className="col-md-12">
                    <h3 className="title-un"><b>Click to Select Room</b></h3>
                    <ul className="home-list">
                        {rooms.length > 0 && rooms.map((room) =>
                            <li key={room.id} className={(room.checked ? 'selected' : '')}
                                onClick={() => getHomerOwner(room)}>
                                <img src={room.imageUrl}/>
                                <span>{room.name}</span>
                            </li>
                        )}
                    </ul>
                </div>


                <div className="col-md-12">
                    <h3 className="title-un"><b>Click Desired Smart Home Functions (Repeat for all rooms in your
                        home)</b></h3>
                    <ul className="home-list">
                        {Object.values(selectedRoom).length > 0 && selectedRoom.home_owners.map((home) =>
                            <li key={home.id} className={(home.checked ? 'selected' : '')}
                                onClick={() => setHomeData(home)}>
                                <img src={home.imageUrl}/>
                                <span>{home.name}</span>
                            </li>
                        )}
                    </ul>
                </div>


                <div className="col-md-12">
                    <div className="text-center">
                        <button onClick={() => saveHomeOwner()} type="button"
                                className="button btn btn-primary">Submit
                            {loader && <div className="loader-btn"></div>}
                        </button>
                    </div>
                </div>
            </div>}

            {submitted && <div className={'row'}>
                <div className="subbmit-msg"><p>Your Quotation request has been submitted</p><p>Thank You</p></div>
            </div>}
        </div>

    );
}


if (document.getElementById('home-owner-with-room')) {
    ReactDOM.render(<HomeOwner/>, document.getElementById('home-owner-with-room'));
}
