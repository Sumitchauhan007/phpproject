import React from 'react';
import ReactDOM from 'react-dom/client';
import './bootstrap';
import Dashboard from './components/Dashboard.jsx';
import UrlsIndex from './components/UrlsIndex.jsx';
import UrlsCreate from './components/UrlsCreate.jsx';
import LoginForm from './components/LoginForm.jsx';
import InvitationsIndex from './components/InvitationsIndex.jsx';
import InvitationsCreate from './components/InvitationsCreate.jsx';

const components = {
    Dashboard,
    UrlsIndex,
    UrlsCreate,
    LoginForm,
    InvitationsIndex,
    InvitationsCreate,
};

const mountReactComponent = (element) => {
    const name = element.dataset.component;
    const Component = components[name];

    if (!Component) {
        if (import.meta.env.DEV) {
            console.warn(`React component "${name}" is not registered.`);
        }
        return;
    }

    let props = {};

    if (element.dataset.props) {
        try {
            props = JSON.parse(element.getAttribute('data-props'));
        } catch (error) {
            console.error(`Failed to parse props for component "${name}"`, error);
        }
    }

    ReactDOM.createRoot(element).render(
        <React.StrictMode>
            <Component {...props} />
        </React.StrictMode>
    );
};

document.querySelectorAll('[data-component]').forEach((element) => {
    mountReactComponent(element);
});
