import React from 'react';

import './styles.css';

import '../../assets/images/bg.png';

export default function Home() {

  return (
    <section className="home-container">
      <div className="home-div-container">
        <div className="home-user">
          <div className="avatar">
            <div className="av av0"></div>
            <button class="selectAvatar icon-pencil"></button>
          </div>

          <div className="containerForm">
            <span>NICK:</span>
            <label>
              <input
                type="text"
                maxlength="18"
                defaultValue="User5614"
              />
            </label>
          </div>

          <div className="actions">
            <button className="btBlueBig ic-rooms">
              <strong>JOGAR!</strong>
            </button>
            <button className="btYellowBig ic-playHome">
              <strong>SALAS</strong>
            </button>

          </div>

        </div>
        <div className="home-login"></div>
      </div>
    </section>
  );

}
