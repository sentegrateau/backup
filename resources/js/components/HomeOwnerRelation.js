import ReactDOM from "react-dom";
import React, {useEffect, useState} from "react";
import http from "../providers/http.provider";

export const HomeOwnerRelation = () => {
    const [rooms, setRooms] = useState([]);
    const [selectedRoom, setSelectedRoom] = useState({});

    useEffect(() => {
        getRooms()
    }, [])


    const getRooms = () => {
        //http.get('room?home=true').then((room) => {
        getHomeOwner((homeOwner) => {
            let data = homeOwner
            if (data && data[0]) {
                setSelectedRoom(data[0]);
            }
            setRooms(data);

        });
        //})
    }

    const getHomeOwner = (cb) => {
        http.get('home-owner').then((data) => {
            cb(data.data);
        })
    }

    const activeData = (data, pkg) => {
        let temp_pkg = [...data];
        const activePackage = temp_pkg.find(x => x.active);
        if (activePackage) {
            // index of active package
            const activePackageIndex = temp_pkg.indexOf(activePackage);
            // de-active the active package
            temp_pkg[activePackageIndex].checked = false;
            temp_pkg[activePackageIndex].active = false;
            // this.unsetSelectedPackageData(this.packageData[activePackageIndex]);
        }
        // finding the selected package

        const findPackage = temp_pkg.find(x => x === pkg);
        if (findPackage) {
            const packageIndex = temp_pkg.indexOf(findPackage);
            temp_pkg[packageIndex].checked = true;
            temp_pkg[packageIndex].active = true;

        }
        return temp_pkg;
    }

    const activateDeactivateRoom = (event, homeOwner) => {

        let temp_pkg = [...rooms];
        const activePackage = temp_pkg.find(x => x.active);

        if (activePackage) {
            const index = temp_pkg.indexOf(activePackage);
            let temp_pkg_element = {...temp_pkg[index]};
            const findRoom = temp_pkg_element.homeOwner.find(x => x === homeOwner);
            if (findRoom) {

                const roomIndex = temp_pkg_element.homeOwner.indexOf(findRoom);

                temp_pkg_element.homeOwner[roomIndex].checked =event.target.checked;

                temp_pkg[index] = temp_pkg_element;

                setSelectedRoom(temp_pkg_element);
                setRooms(temp_pkg);
            }

        }
    }

    const handleRoom = (pkg) => {
        let roomsData = activeData(rooms, pkg);
        setRooms(roomsData);
        setSelectedRoom(pkg);
    }


    const prepareData = () => {
        let temp_pkg = [...rooms];
        const data = [];
        for (let i = 0; i < temp_pkg.length; i++) {

            const activeRooms = temp_pkg[i].homeOwner.filter(x => x.checked);

            for (const room of activeRooms) {
                const result = {};
                result.room_id = temp_pkg[i].id;
                result.home_owner_id = room.id;
                data.push(result);
            }
        }

        return data;
    }


    const getPackageTemplate = () => {
        return rooms.length > 0 && rooms.map((pkg) => {
            return <div key={pkg.id}
                        className={"device-item device-item-pointer " + (pkg.active ? "bg-light-blue" : '')}
                        onClick={() => handleRoom(pkg)}
            >
                <div className="d-flex align-items-center justify-content-between">
                    <div className="package-detail-t1">{pkg.name}</div>
                </div>
            </div>
        })
    }

    const getHomeTemplate = () => {
        if (Object.keys(selectedRoom).length > 0) {
            return selectedRoom.homeOwner.map((pkg) => {
                return <div key={pkg.id} className="col-md-12">
                    <div className="device-items">
                        <label className="custom-control-label"
                               htmlFor={"business-checkbox-" + pkg.id}>
                            <div className="device-item">
                                <div className="d-flex align-items-center justify-content-between">

                                    <div className="package-detail-t1">{pkg.name}</div>
                                    <div className="custom-control custom-checkbox">
                                        <input type="checkbox" className="custom-control-input"
                                               id={"business-checkbox-" + pkg.id}
                                               onChange={(e) => activateDeactivateRoom(e, pkg)}
                                               checked={pkg.checked}/>

                                    </div>

                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            })
        }
    }

    const savePackage = () => {

        const homeOwner = prepareData();

        http.post('home-owner', {homeOwner}).then((response) => {
            new PNotify({
                title: 'Alert',
                text: response.message,
                type: 'success',
                styling: 'bootstrap3',
                delay: 3000
            })

        })

    }

    return (
        <div className="packages-boxes">
            <div className="row">

                <div className="col-md-6">
                    <div className="content-header text-center">
                        <h5 className="mb-0">Select Rooms</h5>
                    </div>
                    <div
                        className="scroll-bars">
                        <div className="device-items">
                            {getPackageTemplate()}
                        </div>
                    </div>
                </div>
                <div className="col-md-6">
                    <div className="content-header text-center">
                        <h5 className="mb-0">Select Home Owner</h5>
                    </div>
                    <div
                        className="scroll-bars">
                        <div className="device-items">
                            {getHomeTemplate()}
                        </div>
                    </div>
                </div>


            </div>
            <div className="d-flex justify-content-between package-ftr-group">

                <button type="submit" className="btn btn-primary btn-next" onClick={savePackage}>
                    <span className="align-middle d-sm-inline-block d-none">Save</span>
                </button>
            </div>
        </div>
    );
}


if (document.getElementById('home-owner-with-room-relation')) {
    ReactDOM.render(<HomeOwnerRelation/>, document.getElementById('home-owner-with-room-relation'));
}
