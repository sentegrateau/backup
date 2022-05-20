import ReactDOM from "react-dom";
import React, {useEffect, useState} from "react";
import http from "../providers/http.provider";

export const FrontPackageDevice = () => {
    const [packages, setPackages] = useState([]);
    const [devices, setDevices] = useState([]);
    const [selectedPackages, setSelectedPackages] = useState({});
    const [selectedDevice, setSelectedDevice] = useState({});

    useEffect(() => {
        getPackages()
    }, [])

    const getPackages = () => {
        http.get('get-package-with-devices').then((data) => {
            console.log(data.data)
            setPackages(pkgs => {
                pkgs = data.data;
                pkgs[0].checked = true;
                return pkgs;
            });
            if (data.data && data.data[0]) {
                data.data[0].checked = true;
                setSelectedPackages(data.data[0])
                data.data[0].devices[0].checked = true;
                getDevices(data.data[0].devices)
            }
        })
    }

    const selectedPkg = (pkg, index) => {
        const tmp_pkg = setCheckedData(packages, pkg);
        pkg.checked = true;
        setSelectedPackages(pkg)
        setPackages(tmp_pkg['allData']);
        getDevices(pkg.devices);
    }

    const getDevices = (devices) => {
        devices[0].checked = true;
        setDevices(devices);
        setSelectedDevice(devices[0]);
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

        let index = tmp_data.findIndex(p => p == data);
        if(index != -1){
            tmp_data[index].checked = true;
        }
        return {allData: tmp_data, index};
    }

    const setDevice = (device) => {
        //console.log(device);
        const tmp_devices = setCheckedData(devices, device);
        device.checked = true;
        setSelectedDevice(device);
        setDevices(tmp_devices['allData'])
    }

    const handleSelect=(e)=>{
        let dev = JSON.parse(e.target.value);
        dev.checked = true;
        setDevice(dev);
    }
    const handlePackage=(e)=>{
        let pack = JSON.parse(e.target.value);
        selectedPkg(pack,0);
    }

    return (
        <div className="container catalogue">

                <h3 className="title-un"><b>Catalogue</b></h3>

            <div className={'catalogue-body'}>
                <div className="row">

                    <div className="col-md-3">
                        <h4>Package Option</h4>
                        <ul className={'list-group react-front-package-ul hidden-xs hidden-md'}>
                            {packages.map((pkg, index) =>
                                <li key={index} style={{cursor: 'pointer'}} onClick={() => selectedPkg(pkg, index)}
                                    className={"package list-group-item " + (pkg.checked ? 'selected' : '')}>
                                    <span>{pkg.name}</span>
                                </li>
                            )}

                        </ul>

                        <select className="form-control react-front-package-select hidden-lg hidden-lg" onChange={handlePackage}>
                            {packages.map((pkg, index) =>
                                <option value={JSON.stringify(pkg)} key={index}>
                                    {pkg.name}
                                </option>
                            )}
                        </select>

                    </div>
                    <div className="col-md-3">
                        <h4>Package Function</h4>
                        <p className="productfunctions">{Object.values(selectedPackages).length > 0 && selectedPackages.description}</p>
                    </div>
                    <div className="col-md-3">
                        <h4>Devices in Packages</h4>
                        <ul className="list-group react-front-device-ul hidden-xs hidden-md">
                            {devices.map((device, index) =>
                                <li onClick={() => setDevice(device)} key={index}
                                    style={{cursor: 'pointer', textAlign: 'right', lineHeight: '50px'}}
                                    className={"list-group-item " + (device.checked ? 'selected' : '')}>
                                    <img src={device.imageUrl} width={50}/>
                                    <span>{device.name}</span>
                                </li>
                            )}
                        </ul>

                        <select className="form-control react-front-device-select hidden-lg hidden-lg" onChange={handleSelect}>
                            {devices.map((device, index) =>
                                <option value={JSON.stringify(device)} key={index}>
                                    {device.name}
                                </option>
                            )}
                        </select>
                    </div>
                    {Object.values(selectedDevice).length > 0 &&
                    <div className="col-md-3">
                        <h4>Device Function</h4>
                        <p className="title">{selectedDevice.name}</p>
                        <b>Key Functions: </b><br/>
                        <p className="description">{selectedDevice.description}</p>
                    </div>}
                </div>
            </div>
        </div>
    );
}


if (document.getElementById('front-packages-devices')) {
    ReactDOM.render(<FrontPackageDevice/>, document.getElementById('front-packages-devices'));
}
