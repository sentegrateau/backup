import http from '../providers/http.provider';
import React, {useEffect, useState} from "react";
import ReactDOM from 'react-dom'

const Devices = ({data, selectDevice}) => {
    return (<ul className="col-md-2 list-group">
        {data.map((device) => {
            return (<li key={device.id} onClick={() => selectDevice(device)}
                        className={"list-group-item " + ((device.checked) ? 'bg-light-blue' : '')}>{device.name}</li>)
        })}
    </ul>)
}

const Room = ({data, getDevice}) => {
    return (<ul className="col-md-2 list-group">
        {data.map((room) => {
            return (
                <li key={room.id} onClick={() => getDevice(room)}
                    className={"list-group-item " + ((room.checked) ? 'bg-light-blue' : '')}>{room.name}</li>)
        })}
    </ul>)
}

const Package = ({data, getRoom}) => {
    return (<ul className="col-md-2 list-group">
        {data.map((pkg) => {
            return (<li key={pkg.id} onClick={() => getRoom(pkg)}
                        className={"list-group-item " + ((pkg.checked) ? 'bg-light-blue' : '')}>{pkg.name}</li>)
        })}
    </ul>)
}

const StandardKit = () => {
    const [packages, setPackages] = useState([]);
    const [rooms, setRooms] = useState([]);
    const [devices, setDevices] = useState([]);
    const [cart, setCart] = useState(cartValues || {});
    const [selectedDevice, setSelectedDevice] = useState({});


    useEffect(() => {
        getPackages()
    }, [])

    const getRoom = (pkg) => {
        http.get('package-rooms/' + pkg.id + '/' + window.kitData.id).then((data) => {
            if (data.data.length > 0) {
                setRooms(rooms => {
                    rooms = data.data;
                    rooms[0].checked = true;
                    return rooms;
                });

                if (data.data && data.data[0]) {
                    defaultDevice(data.data[0].devices)
                }
                if (pkg)
                    packageSet(pkg)
            }
        })
    }

    const defaultDevice = (devices) => {
        if (devices[0]) {
            devices[0].checked = true

            setDevices(devices);
            let pkgs = [...packages];

            devices[0].qty = (devices[0].cart_qty) ? devices[0].cart_qty : devices[0].min_qty;
            setSelectedDevice(devices[0])
        } else {
            setSelectedDevice({})
        }
    }

    const getPackages = () => {
        http.get('package').then((data) => {
            setPackages(pkgs => {
                pkgs = data.data;
                pkgs[0].checked = true;
                return pkgs;
            });
            if (rooms.length <= 0 && data.data && data.data[0]) {
                data.data[0].checked = true;
                getRoom(data.data[0])
            }
        })
    }

    const getDevice = (room) => {
        roomSet(room)
        setDevices(room.devices);
    }

    const deviceSet = (device) => {
        const tmp_devices = setCheckedData(devices, device);
        setDevices(tmp_devices['allData']);
        device.qty = (device.cart_qty) ? device.cart_qty : device.min_qty;
        setSelectedDevice(device);
    }

    const packageSet = (pkg) => {
        const tmp_pkg = setCheckedData(packages, pkg);
        setPackages(tmp_pkg['allData']);
    }

    const roomSet = (room) => {
        const tmp_room = setCheckedData(rooms, room);
        if (room.devices) {
            defaultDevice(room.devices)
        }
        setRooms(tmp_room['allData']);
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

    const addToCart = () => {
        let pkgs = [...packages];
        let rms = [...rooms];
        let tmp_devices = [...devices];
        let selectedPkg = findActive(pkgs);
        let selectedRoom = findActive(rms);
        let cartObj = {...cart};

        if (Object.keys(selectedDevice).length > 0 && selectedDevice.qty > 0) {
            const activeIndex = tmp_devices.findIndex(p => {
                return p.id === selectedDevice.id
            });
            if (tmp_devices[activeIndex])
                tmp_devices[activeIndex].cart_qty = selectedDevice.qty;


            const id = selectedPkg.id + '-' + selectedRoom.id + '-' + selectedDevice.id;
            cartObj[id] = {
                name: {device: selectedDevice.name, pkg_name: selectedPkg.name, room_name: selectedRoom.name},
                amt: selectedDevice.price,
                package_id: selectedPkg.id,
                room_id: selectedRoom.id,
                device_id: selectedDevice.id,
                quantity: selectedDevice.qty
            };
            setCart(cartObj);
            setDevices(tmp_devices);
        } else {
            const id = selectedPkg.id + '-' + selectedRoom.id + '-' + selectedDevice.id;
            delete cartObj[id];
            const activeIndex = tmp_devices.findIndex(p => {
                return p.id === selectedDevice.id
            });
            if (tmp_devices[activeIndex])
                tmp_devices[activeIndex].cart_qty = selectedDevice.qty;
            setCart(cartObj);
            setDevices(tmp_devices);
        }
    }

    const incrementDecrement = (actionType) => {
        let tmp_selectedDevice = {...selectedDevice};
        if (tmp_selectedDevice === undefined) {
            return;
        }
        if (actionType === '+') {
            if (tmp_selectedDevice.qty === tmp_selectedDevice.max_qty) {
                return;
            }
            tmp_selectedDevice.qty += 1;
        } else {
            if (tmp_selectedDevice.qty === tmp_selectedDevice.min_qty) {
                return;
            }
            tmp_selectedDevice.qty -= 1;
        }
        setSelectedDevice(tmp_selectedDevice);
    }

    const changeQty = (event) => {

    }

    const getTotal = () => {
        let total = 0;
        if (Object.values(cart).length > 0) {
            for (const crt of Object.values(cart)) {
                total += crt.amt * crt.quantity;
            }
        }
        return total.toFixed(2);
    }

    const saveStandardPkg = () => {
        var reqData = {
            "id": kitData.id,
            "_method": "PUT",
            "user_id": kitData.user_id,
            "partner_id": kitData.user_id,
            "type": kitData.type,
            "title": kitData.title,
            "amount": 0,
            "draft_items": Object.values(cart)
        };
        //standardPkgUrl
        http.post(window.standardPkgUrl, reqData).then((data) => {
            new PNotify({
                title: 'Alert',
                text: data.message,
                type: 'success',
                styling: 'bootstrap3',
                delay: 3000
            })
        })
    }

    const removeItem = (e, index) => {
        e.preventDefault();
        let tmp_cart = {...cart};
        delete tmp_cart[index]
        setCart(tmp_cart);
    }


    return (
        <div>

            <Package data={packages} getRoom={getRoom}/>
            <Room data={rooms} getDevice={getDevice}/>
            <Devices data={devices} selectDevice={deviceSet}/>
            <div className="col-md-3 pos-column pos-column-description">
                {selectedDevice && <div style={{padding: '10px'}}>
                    <p className="title">
                        {selectedDevice.name}
                    </p>

                    <b>Device Id:</b> {selectedDevice.device_id}<br/>
                    <b>Model:</b> {selectedDevice.model}<br/>
                    <b>Brand:</b> {selectedDevice.brand}<br/>
                    <b>Key Functions:</b> {selectedDevice.description}<br/>
                    <p className="description" style={{textOverflow: 'initial'}}>
                    </p>
                    <div className="row">
                        <div className="col-sm-6">
                            <div className="input-group">
                                <span className="input-group-btn">
                                  <button onClick={() => incrementDecrement('-')} type="button"
                                          className="btn btn-default btn-number"
                                          id="ajax-btn-decrement">
                                    <span className="glyphicon glyphicon-minus"/>
                                  </button>
                                </span>
                                <input id="add-to-cart-item" type="text" className="form-control input-number"
                                       value={selectedDevice.qty || 0} min={selectedDevice.min_qty} onChange={changeQty}
                                       max={selectedDevice.max_qty}/>
                                <span className="input-group-btn">
                                  <button onClick={() => incrementDecrement('+')} id="ajax-btn-increment" type="button"
                                          className="btn btn-default btn-number">
                                    <span className="glyphicon glyphicon-plus"/>
                                  </button>
                                </span>
                            </div>
                        </div>
                        <div className="col-sm-3">
                            <a className={'btn btn-primary'} onClick={addToCart}>Add</a>
                        </div>
                    </div>
                </div>}
            </div>
            <div className="col-md-3">
                <div className="">
                    <table className="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            {/*<th>Action</th>*/}
                        </tr>
                        </thead>
                        <tbody>
                        {Object.values(cart).length > 0 && Object.keys(cart).map((item, index) => {
                            return (
                                <tr key={index}>
                                    <td>
                                        <span style={{
                                            "width": "100%",
                                            "float": "left"
                                        }}>{cart[item]['name']['device']}</span>
                                        <span style={{
                                            "color": "rgb(138 138 138)",
                                            "fontSize": "13px"
                                        }}>({cart[item]['name']['pkg_name']}-{cart[item]['name']['room_name']})</span>
                                    </td>
                                    <td>{cart[item].quantity}</td>
                                    <td>${cart[item].amt}</td>
                                    {/*<td><a href={'#'} onClick={(e) => removeItem(e, item)}><i
                                        className={'fa fa-trash'}></i></a></td>*/}
                                </tr>
                            )
                        })}
                        <tr>
                            <td>Total</td>
                            <td>&nbsp;</td>
                            <td><span>${getTotal()}</span></td>
                            <td>&nbsp;</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
                <button className={'btn btn-primary'} onClick={saveStandardPkg}>Save Standard Kit</button>
            </div>
        </div>
    )
}

if (document.getElementById('make-packages')) {
    ReactDOM.render(<StandardKit/>, document.getElementById('make-packages'));
}
