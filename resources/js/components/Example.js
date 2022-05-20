import http from '../providers/http.provider';
import React, {useEffect, useState} from "react";
import ReactDOM from 'react-dom'

const Package = ({packages}) => {
    return (<ul className="col-md-3 list-group">
        {packages.map((pkg) => {
            return (<li className="list-group-item">{pkg.name}</li>)
        })}
    </ul>)
}
const MakePackage = () => {
    const [packages, setPackages] = useState([]);

    useEffect(() => {
        getPackages()
    }, [])

    const getPackages = () => {
        http.get('package').then((data) => {
            setPackages(data.data);
        })
    }

    return (
        <div>
            <Package packages={packages}/>
        </div>
    )
}

if (document.getElementById('make-packages')) {
    ReactDOM.render(<MakePackage/>, document.getElementById('make-packages'));
}
