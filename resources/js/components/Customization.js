import React, {useEffect, useState} from 'react';
import ReactDOM from "react-dom";
import http from "../providers/http.provider";

const Customization = () => {

    const [rooms, setRooms] = useState([]);
    const [devices, setDevices] = useState([]);
    const [selectedRoom, setSelectedRoom] = useState({});
    const [selectedDevice, setSelectedDevice] = useState({});


    useEffect(() => {
        // customization-rooms/
        getRooms()
    }, [])

    const getRooms = () => {
        if (window.orderId) {
            http.get('customization-rooms/' + window.orderId).then((data) => {
                const allRooms = [...data.data.rooms];
                setRooms(allRooms);
                //getDevices(data.data.rooms[0].id)
                setSelectedRoom(data.data.rooms[0])
                setDevices(data.data.rooms[0].devices);
                setSelectedDevice(data.data.rooms[0].devices[0])
            })
        }
    }

    const getDevices = (roomId) => {
        if (window.orderId) {
            http.get('customization-devices/' + window.orderId + '/' + roomId).then((data) => {
                const allDevices = [...data.data.devices];
                setDevices(allDevices);
                setSelectedDevice(allDevices[0])
            })
        }
    }

    const handleChangeRoom = (e, index) => {
        let allRooms = [...rooms];
        allRooms[index].customName = e.target.value;
        setRooms(allRooms);

    }

    const handleChangeDevice = (e, index) => {
        let allDevices = [...devices];
        allDevices[index].name = e.target.value;
        setDevices(allDevices);
    }

    const handleSelectedRoom = (e, index) => {
        let allRooms = [...rooms];
        setSelectedRoom(allRooms[index])
        setDevices(allRooms[index].devices);
        setSelectedDevice(allRooms[index].devices[0])
    }

    const handleSelectedDevice = (e, index) => {
        let allDevices = [...devices];
        setSelectedDevice(allDevices[index])
    }


    const editRoom = (e, index, type) => {
        let allRooms = [...rooms];

        allRooms[index]['edit'] = (type == 'edit') ? true : false;
        setRooms(allRooms)
    }

    const editDevice = (e, index, type) => {
        let allDevices = [...devices];

        allDevices[index]['edit'] = (type == 'edit') ? true : false;
        setDevices(allDevices)
    }

    const handleSubmit = () => {
        console.log(rooms)
        console.log(devices)
    }

    const handleRoomChecked = (index) => {
        let allRooms = [...rooms];
        allRooms[index]['order_items_count'] = !allRooms[index]['order_items_count'];
        if (allRooms[index]['order_items_count'] === false) {
            //allRooms[index]
        }
        setRooms(allRooms)
    }

    const handleDeviceChecked = (index) => {
        let allDevices = [...devices];
        allDevices[index]['order_items_count'] = !allDevices[index]['order_items_count'];
        setDevices(allDevices)
    }


    return (
        <div className="container customization-container">
            <div className="kit-head">
                <div className="form-group">
                    <select className="form-control">
                        <option>Select Home Center</option>
                        <option>Select Home Center 1</option>
                        <option>Select Home Center 1</option>
                        <option>Select Home Center 1</option>
                    </select>
                </div>
                <div className="form-group">
                    <button className="btn btn-kit" onClick={handleSubmit}>save</button>
                    <button className="btn btn-kit">Next</button>
                </div>
            </div>
            <div className="row">
                <div className="col-md-3">
                    <h3 className="kit-heading">1. Customer Details</h3>
                    <div className="kit-border  left-kit-aside">
                        <div className="kit-customer-input">
                            <label>Username</label>
                            <input type="text"/>
                        </div>
                        <div className="kit-customer-input">
                            <label>Email:</label>
                            <input type="text"/>
                        </div>
                        <div className="kit-customer-input">
                            <label>Address line 1:</label>
                            <input type="text"/>
                        </div>
                        <div className="kit-customer-input">
                            <label>Address line 2:</label>
                            <input type="text"/>
                        </div>
                        <div className="kit-customer-input">
                            <label>Suburb:</label>
                            <input type="text"/>
                        </div>
                        <div className="kit-customer-input post-input">
                            <label>State:</label>
                            <div>
                                <input type="text"/>
                                <label style={{textAlign: 'center'}}>Post Code:</label>
                                <input type="text"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-md-9">
                    <div className="kit-flex-wrapper">
                        <div className="rooms-kit">
                            <h3 className="kit-heading">2. Customize Rooms </h3>
                            <div className="kit-border divi-scroll">
                                {rooms.map((room, index) => {
                                    return <div key={index}
                                                className={(selectedRoom.id === room.id) ? 'gray-input active' : 'gray-input'}>

                                        <input type={'checkbox'} onChange={(e) => handleRoomChecked(index)}
                                               checked={room.order_items_count}/>
                                        {room.edit &&
                                        <React.Fragment>
                                            <input type="text" value={room.customName}
                                                   name={'room' + index}
                                                   onChange={(e) => handleChangeRoom(e, index)}
                                            />
                                            <span className="kit-edit-close"
                                                  onClick={(e) => editRoom(e, index, 'close')}>
                                            <i className="fas fa-times"/>
                                        </span>
                                        </React.Fragment>
                                        }
                                        {!room.edit &&
                                        <React.Fragment>
                                            <label
                                                onClick={(e) => handleSelectedRoom(e, index)}>{room.customName}</label>
                                            <span className="kit-edit-close"
                                                  onClick={(e) => editRoom(e, index, 'edit')}>
                                            <i className="far fa-edit"/>
                                            </span>
                                        </React.Fragment>}

                                    </div>

                                })}
                            </div>
                        </div>
                        <div className="divice-kit">
                            <h3 className="kit-heading">3A. Customize Device Names
                                <div className="checkbox switcher">
                                    <label htmlFor="test">
                                        <span>Show/Hide Inactive Devices</span>
                                        <input type="checkbox" id="test" defaultChecked={true}/>
                                        <span><small/></span>
                                    </label>
                                </div>
                            </h3>
                            <div className="kit-border">
                                <div className="divice-inner-scroll">
                                    <div className="rooms-kit  divi-scroll">
                                        {devices.map((device, index) => {
                                            return device && <div key={index} className={(selectedDevice.id === device.id) ? 'gray-input active' : 'gray-input'}>
                                                <input type={'checkbox'} checked={device.order_items_count}
                                                       onChange={(e) => handleDeviceChecked(index)}/>
                                                {device.edit &&
                                                <React.Fragment>
                                                    <input type="text" value={device.name}
                                                           name={'device' + index}
                                                           onChange={(e) => handleChangeDevice(e, index)}
                                                    />
                                                    <span className="kit-edit-close"
                                                          onClick={(e) => editDevice(e, index, 'close')}>
                                            <i className="fas fa-times"/>
                                        </span>
                                                </React.Fragment>
                                                }
                                                {!device.edit &&
                                                <React.Fragment>
                                                    <label
                                                        onClick={(e) => handleSelectedDevice(e, index)}>{device.name}</label>
                                                    <span className="kit-edit-close"
                                                          onClick={(e) => editDevice(e, index, 'edit')}>
                                            <i className="far fa-edit"/>
                                            </span>
                                                </React.Fragment>}
                                            </div>
                                        })}
                                    </div>
                                    <div className="divice-add-remove">
                                        <div className="flex-divice-add-remove">
                                            {selectedDevice && selectedDevice.device_feature_functions && selectedDevice.device_feature_functions.map((functions) =>
                                                <div className="label-item">
                                                    <label>{functions.label}:</label>
                                                    <input type="text" defaultValue={functions.name}/>
                                                </div>)}

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h3 className="kit-heading parameters-heading">3B. Device Parameters </h3>
                            <div className="kit-border parameters-box">
                                <div className="custon-radio-kit custom_radio">

                                    {selectedDevice && selectedDevice.device_feature_params && selectedDevice.device_feature_params.map((param) =>
                                        <React.Fragment>
                                            <span>{param.label}:</span>
                                            <div className="parameters-item">
                                                <input type="radio" id="test1" name="radio-group"/>
                                                <label htmlFor="test1">{param.name}</label>
                                            </div>
                                        </React.Fragment>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Customization;

if (document.getElementById('customization-root')) {
    ReactDOM.render(
        <Customization/>, document.getElementById('customization-root'));
}
