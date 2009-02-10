/*
 * Fire (Flexible Interface Rendering Engine) is a set of graphics widgets for creating GUIs for j2me applications. 
 * Copyright (C) 2006-2008 Bluevibe (www.bluevibe.net)
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */

package gr.fire.core;

import javax.microedition.lcdui.Graphics;


/**
 * <code>Animation</code>可以用于实现转换特效，比如应用在容器状态放生改变时。
 * 或者直接用于在<coe>Component</code>内部显示
 * Animation are either used as transitions effects (used when the state of a Container changes) 
 * or as Animations inside a component.
 * <br/>
 * 转换特效可以在容器添加或移除组件时显示，或者容器显示或隐藏是显示
 * A transition effect can be displayed for example when a component is added or 
 * removed from the Container or when the container is made visible or disabled etc.
 * The transition effect will be triggered if there is a 
 * Animation instance associated with the transition the container is going through.
 * <br/>
 * An animation inside a Component can be for example the movement of the dials of an AnalogClock component.
 * This type of animation is continious (i.e. it will not complete).
 * <br/>
 * An animation is confined inside its owner. It is not allowed to paint outside the 
 * clipping rectangle defined by owner.x,owner.y,owner.width,owner.height.
 * <br/>
 * If the owner of the animation is null, then it is considered to be owned by the FireScreen 
 * and it is not confined to a bounding box.
 *
 * 
 * @author padeler
 *
 */
public abstract class Animation extends Component
{
	/**
     * 初始化动画对象，并设置父组件
     * @param parent
     */
	public Animation(Component parent)
	{
		this.parent=parent;
	}
	/**
     * 初始化一个空动画对象
     */
	public Animation()
	{
	}
	
	
	/**
	 * 动画中它到达最终状态前都是出于播放中状态
     * 该防范不应该修改动画的状态，并应该尽快返回
	 * An animation is running when it has not yet reached its final state.
	 * This function should not alter the state of the animation and should return fast.
	 * 
	 * @return true, 动画播放中。if this Animation is running.
	 * 
	 * @see  Animation#step()
	 */
	public abstract boolean isRunning();
	
	/**
     * 将动画设置到下一帧，该方法应该以最快的速度返回
	 * Causes this animation to move to its next frame. 
	 * This method should return as fast as possible.
	 *  
	 * @return true, if the animation needs to be re-drawn on the screen. false otherwise.
	 */
	public abstract boolean step();
	
	
	/**
     * 停止动画播放，动画的状态将被设置到最后的状态上。
     * 如果紧接着调用<code>getCurrentFrame()</code>方法应该返回动画的最后一帧
	 * Moves the animation to its last state. 
	 * Subsequent calls to getCurrentFrame() should return the final frame of the animation. 
	 */
	public abstract void stop();
	
	/**
	 * ？？？？
	 * @return an image containing the current frame of this effect. 
	 */
	public abstract void paint(Graphics g);
	
}