import React from 'react';
import HomePage from './homepage/HomePage';
import App from './app/App';

import navigators from './configs/navigators.js';

export default function Platform() {
	const state = [];

	return (
		<>
			<App navigator={navigators.filter((nav) => nav.name == 'app')[0]} />
		</>
	);
}
