import React from "react";

export default class Administration extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            users: []
        };

        this.getUsers();
    }

    deleteUser(id) {
        if ( window.confirm( "really delete user " + id + "?" ) ) {
            $.ajax({
                url: "/u/" + id + "/",
                type: 'DELETE'
            }).done((response) => {
                this.getUsers();
            });
        }
    }

    getUsers() {
        $.get("/users/").done((response) => {
            this.setState({
                users: response
            });
        });
    }

    render() {
        let userRows = this.state.users.map((user) => {
            return (
                <tr key={user.id}>
                    <td>{user.id}</td>
                    <td>
                        <a href={"/u/" + user.id + "/profile"}>{user.name}</a>
                    </td>
                    <td>{user.email}</td>
                    <td><a className="button-red small" onClick={() => { this.deleteUser(user.id) }}>X</a></td>
                </tr>
            );
        });

        return (
            <div className="user-list">
                <h2>Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        {userRows}
                    </tbody>
                </table>
            </div>
        );
    }
}