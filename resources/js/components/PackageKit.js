import ReactDOM from "react-dom";
import React, {useEffect, useState} from "react";
import http from "../providers/http.provider";

const PackageKit = () => {
    const [packagesRoomData, setPackagesRoomData] = useState([]);
    const [selectedPkg, setSelectedPkg] = useState({});

    useEffect(() => {
        getPackages()
    }, [])

    const getPackages = () => {
        http.get('package').then((pkg) => {
            getRooms((rooms) => {
                getSelectedPackageRoom((device) => {
                    let data = pkg.data.map((e, index) => {
                            return {
                                ...e,
                                checked: (index === 0),
                                active:(index === 0),
                                rooms: rooms.map((room) => {
                                    return {
                                        ...room,
                                        checked: false,
                                        min_qty: 0,
                                        max_qty: 0
                                    };
                                })
                            };
                        }
                    );
                    setSelectedPkg(data[0]);

                    if (device && device.packages_rooms_devices.length > 0) {
                        data = setPackageAndRoomWithQty(data, device.packages_rooms_devices);
                    }

                    setPackagesRoomData(data);
                });
            })
        })
    }

    const getRooms = (cb) => {
        http.get('room').then((data) => {
            cb(data.data);
        })
    }

    const getSelectedPackageRoom = (cb) => {
        http.get('device/' + window.DEVICE_ID).then((data) => {
            cb(data.data);
        })
    }

    const setPackageAndRoomWithQty = (data, packagesRoomsDevices) => {
        for (const packageRoomDevice of packagesRoomsDevices) {
            const findPackage = data.find(x => x.id === packageRoomDevice.package_id);

            if (findPackage) {
                const packageIndex = data.indexOf(findPackage);

                if (data[packageIndex].checked === false) {
                    data[packageIndex].checked = true;
                }

                const findRoom = data[packageIndex].rooms.find(x => x.id === packageRoomDevice.room_id);

                if (findRoom) {

                    const roomIndex = data[packageIndex].rooms.indexOf(findRoom);

                    data[packageIndex].rooms[roomIndex].checked = true;
                    data[packageIndex].rooms[roomIndex].min_qty = packageRoomDevice.min_qty;
                    data[packageIndex].rooms[roomIndex].max_qty = packageRoomDevice.max_qty;
                }
            }
        }
        return data;
    }

    const activatePackage = (pkg) => {
        let temp_pkg = [...packagesRoomData];
        const activePackage = temp_pkg.find(x => x.checked);
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
        console.log(findPackage)
        if (findPackage) {
            const packageIndex = temp_pkg.indexOf(findPackage);

            temp_pkg[packageIndex].checked = true;
            temp_pkg[packageIndex].active = true;
            setPackagesRoomData(temp_pkg);
            setSelectedPkg(temp_pkg[packageIndex])
        }
    }

    const activateDeactivateRoom = (event, room) => {

        let temp_pkg = [...packagesRoomData];
        const activePackage = temp_pkg.find(x => x.checked);
        if (activePackage) {
            const index = temp_pkg.indexOf(activePackage);
            let temp_pkg_element = {...temp_pkg[index]};
            const findRoom = temp_pkg_element.rooms.find(x => x === room);
            if (findRoom) {

                const roomIndex = temp_pkg_element.rooms.indexOf(findRoom);

                temp_pkg_element.rooms[roomIndex].checked = event.target.checked;
                if (event.target.checked === false) {
                    temp_pkg_element.rooms[roomIndex].min_qty = 0;
                    temp_pkg_element.rooms[roomIndex].max_qty = 0;
                }
                temp_pkg[index] = temp_pkg_element;
                setPackagesRoomData(temp_pkg);
                setSelectedPkg(temp_pkg_element)
            }

        }
    }

    const changeMinQty = (event, room) => {
        if (Object.keys(selectedPkg).length > 0) {
            let temp_pkg = [...packagesRoomData];
            const packageIndex = temp_pkg.indexOf(selectedPkg);
            const findRoom = temp_pkg[packageIndex].rooms.find(x => x === room);
            const roomIndex = temp_pkg[packageIndex].rooms.indexOf(findRoom);
            const roomData = temp_pkg[packageIndex].rooms[roomIndex];
            if (roomData.max_qty > 0) {
                if (Number(event.target.value) > roomData.max_qty) {

                    new PNotify({
                        title: 'Alert',
                        text: 'Max value should be greater than minimum value',
                        type: 'error',
                        styling: 'bootstrap3',
                        delay: 3000
                    })

                }
            }
            roomData.min_qty = event.target.value;
            setPackagesRoomData(temp_pkg);
            setSelectedPkg(temp_pkg[packageIndex])
        }
    }
    const changeMaxQty = (event, room) => {
        if (Object.keys(selectedPkg).length > 0) {
            let temp_pkg = [...packagesRoomData];
            const packageIndex = temp_pkg.indexOf(selectedPkg);
            const findRoom = temp_pkg[packageIndex].rooms.find(x => x === room);
            const roomIndex = temp_pkg[packageIndex].rooms.indexOf(findRoom);
            const roomData = temp_pkg[packageIndex].rooms[roomIndex];
            if (roomData.min_qty > 0) {
                if (roomData.min_qty > Number(event.target.value)) {
                    new PNotify({
                        title: 'Alert',
                        text: 'Max value should be greater than minimum value',
                        type: 'error',
                        styling: 'bootstrap3',
                        delay: 3000
                    })

                }
            }
            roomData.max_qty = event.target.value;
            setPackagesRoomData(temp_pkg);
            setSelectedPkg(temp_pkg[packageIndex])
        }
    }

    const prepareData = () => {
        let temp_pkg = [...packagesRoomData];
        const activePackage = temp_pkg.find(x => x.checked);
        const data = [];
        for (let i = 0; i < temp_pkg.length; i++) {

            const activeRooms = temp_pkg[i].rooms.filter(x => x.checked);

            for (const room of activeRooms) {
                if (Number(room.min_qty) > Number(room.max_qty)) {
                    break;
                }
                const result = {};
                result.package_id = temp_pkg[i].id;
                result.room_id = room.id;
                result.min_qty = Number(room.min_qty);
                result.max_qty = Number(room.max_qty);
                data.push(result);
            }
        }

        return data;
    }

    const savePackage = () => {
        if (window.DEVICE_ID) {
            const quantities = prepareData();

            http.post('device/savePackageRoom', {quantities, id: window.DEVICE_ID}).then((response) => {
                new PNotify({
                    title: 'Alert',
                    text: response.message,
                    type: 'success',
                    styling: 'bootstrap3',
                    delay: 3000
                })

            })
        }
    }


    const getPackageTemplate = () => {
        return packagesRoomData.length > 0 && packagesRoomData.map((pkg) => {
            return <div key={pkg.id}
                        className={"device-item device-item-pointer " + (pkg.active ? "bg-light-blue" : '')}
                        onClick={() => activatePackage(pkg)}>
                <div className="d-flex align-items-center justify-content-between">
                    <div className="package-detail-t1">{pkg.name}</div>
                </div>
            </div>
        })
    }

    const getRoomTemplate = () => {
        if (Object.keys(selectedPkg).length > 0) {
            return selectedPkg.rooms.map((room) => {
                return (
                    <div key={room.id} className="row">
                        <div className="col-md-4">
                            <div className="device-items">
                                <label className="custom-control-label"
                                       htmlFor={"business-checkbox-" + room.id}>
                                    <div className="device-item">
                                        <div className="d-flex align-items-center justify-content-between">

                                            <div className="package-detail-t1">{room.name}</div>
                                            <div className="custom-control custom-checkbox">
                                                <input type="checkbox" className="custom-control-input"
                                                       id={"business-checkbox-" + room.id}
                                                       onChange={(e) => activateDeactivateRoom(e, room)}
                                                       checked={room.checked}/>

                                            </div>

                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div className="col-md-4">
                            <div className="device-items">
                                <input type="number" placeholder="Minimum Quantity" value={room.min_qty}
                                       onChange={(e) => changeMinQty(e, room)} className="form-control"
                                       disabled={!room.checked}/>
                            </div>
                        </div>
                        <div className="col-md-4">
                            <div className="device-items">
                                <input type="number" placeholder="Maximum Quantity" value={room.max_qty}
                                       className="form-control" onChange={(e) => changeMaxQty(e, room)}
                                       disabled={!room.checked}/>
                            </div>
                        </div>
                    </div>)
            });
        }
        return <div></div>
    }

    const previousBack = () => {
        window.location.href = window.previousUrl;
    }


    return (
        <div className="packages-boxes">
            <div className="row">
                <div>
                    <div className="col-md-3">
                        <div className="content-header text-center">
                            <h5 className="mb-0">Select Package</h5>
                        </div>
                        <div
                            className="scroll-bars">
                            <div className="device-items">
                                {getPackageTemplate()}
                            </div>
                        </div>
                    </div>
                    <div className="col-md-9">
                        <ng-container ngif="selectedPackage; else empty">
                            <div className="row text-center">
                                <div className="col-md-4">
                                    <div className="content-header text-center">
                                        <h5 className="mb-0">Rooms</h5>
                                    </div>
                                </div>
                                <div className="col-md-4">
                                    <div className="content-header text-center">
                                        <h5 className="mb-0">Minimum Quantity</h5>
                                    </div>
                                </div>
                                <div className="col-md-4">
                                    <div className="content-header text-center">
                                        <h5 className="mb-0">Maximum Quantity</h5>
                                    </div>
                                </div>
                            </div>
                            <div className={'scroll-bars'}>
                                {getRoomTemplate()}
                            </div>
                        </ng-container>
                    </div>
                </div>
            </div>
            <div className="d-flex justify-content-between package-ftr-group">
                <button onClick={previousBack} type="button" className="btn btn-outline-secondary btn-prev">
                    <i data-feather="arrow-left" className="align-middle mr-sm-25 mr-0"/>
                    <span className="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
                <button type="submit" className="btn btn-primary btn-next" onClick={savePackage}>
                    <span className="align-middle d-sm-inline-block d-none">Save</span>
                </button>
            </div>
        </div>
    );
}

if (document.getElementById('device-packages')) {
    ReactDOM.render(<PackageKit/>, document.getElementById('device-packages'));
}
