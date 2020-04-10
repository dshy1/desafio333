import React from 'react';

import './styles.css';

import '../../assets/images/bg.png';

export default function Home() {

  return (
    <section className="home-container">
      <h1>B<span>rig</span>htC<span>ode</span></h1>
      <div className="home-div-container">
        <h2 className="sub-title">ESCOLHA UM NICK E AVATAR</h2>
        <div className="home-user">
          <div className="avatar">
            <div className="av av0"></div>
            <button className="selectAvatar icon-pencil" />
          </div>

          <div className="containerForm">
            <span className="icon-lock">NICK:</span>
            <label>
              <input
                type="text"
                maxLength="18"
                defaultValue="User5614"
              />
            </label>
          </div>

          <div className="links">
            <a className="play" href="goog.com">JOGAR!</a>
            <a className="rooms" href="goog.com">SALAS</a>
          </div>
        </div>
        <div class="or"><span>OU</span></div>
        <div className="home-login">
          <h2 className="social">FAÇA LOGIN OU CRIE UMA CONTA</h2>

          <div className="form-container">
            <form>
              <label htmlFor="email">E-mail</label>
              <input type="email" id="email" placeholder="Digite seu email" /><br />

              <label htmlFor="pass">Senha</label>
              <input type="password" id="pass" placeholder="Digite sua senha" />

              <div className="buttons">
                <button className="login" >Entrar</button>
                <button className="register" >Cadastrar</button>
              </div>

            </form>
          </div>
        </div>
      </div>

      <div className="links-mobile">
        <a className="play-mobile" href="goog.com">JOGAR!</a>
        <a className="rooms-mobile" href="goog.com">SALAS</a>
      </div>
    </section>
  );

}
