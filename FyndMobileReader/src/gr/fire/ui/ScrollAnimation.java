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

/**
 * 
 */
package gr.fire.ui;

import gr.fire.core.Animation;
import gr.fire.core.FireScreen;
import gr.fire.core.Panel;

import javax.microedition.lcdui.Graphics;

/**
 * @author padeler
 *
 */
public class ScrollAnimation extends Animation
{
	public static final long MILISECONDS_PER_FRAME=40;
	
	public static final int SCROLL_FRAMES = 3; // number of frames in this animation.
	
	
	private int scrollPercent = 35; // percent of the viewports dimensions to scroll.
	private int direction;
	private int frameCount=0;
	private int stepChange=0;
	private long lastFrame;
	int width,height;
	
	public void paint(Graphics g)
	{
		parent.paint(g);
	}
	
	
	
	public boolean isRunning()
	{
		return (frameCount<SCROLL_FRAMES);
	}

	/**
	 * Setup this scrollAnimation object. 
	 * @param Owner is the Panel which will scroll
	 * @param Trigger is ignored.
	 * @param properties is an Integer with the animation direction. Animation directions are FireScreen.LEFT, FireScreen.RIGHT, FireScreen.UP, FireScreen.DOWN. 
	 */
	public ScrollAnimation(Panel destinationPanel,int direction,int percent)
	{
		super(destinationPanel);
		this.direction= direction;
		this.scrollPercent= percent;
		
		int vpD;
		if(direction==FireScreen.UP || direction==FireScreen.DOWN)
			vpD = destinationPanel.getViewPortHeight();
		else 
			vpD = destinationPanel.getViewPortWidth();
		
		
		
		stepChange = ((vpD * scrollPercent)/SCROLL_FRAMES) / 100;
		width = destinationPanel.getWidth();
		height = destinationPanel.getHeight();
		
		lastFrame = System.currentTimeMillis();
	}

	/* (non-Javadoc)
	 * @see gr.fire.core.Animation#step()
	 */
	public boolean step()
	{
		long now = System.currentTimeMillis();
		
		if(now-lastFrame>=MILISECONDS_PER_FRAME)
		{
			Panel destinationPanel = (Panel)parent; 
			lastFrame = now;
			frameCount++;
			int vpX = destinationPanel.getViewPortPositionX();
			int vpY = destinationPanel.getViewPortPositionY();
			int d = direction;
			boolean dontStop=true;
			switch(d)
			{
			case FireScreen.UP:
				dontStop = destinationPanel.setViewPortPosition(vpX,vpY-stepChange);
				break;
			case FireScreen.DOWN:
				dontStop = destinationPanel.setViewPortPosition(vpX,vpY+stepChange);
				break;
			case FireScreen.LEFT:
				dontStop = destinationPanel.setViewPortPosition(vpX-stepChange,vpY);
				break;
			case FireScreen.RIGHT:
				dontStop = destinationPanel.setViewPortPosition(vpX+stepChange,vpY);
				break;
			}
			
			if(dontStop==false)
			{
				stop();
				return false;
			}
			return true;
		}
		return false;
	}
	
	public void forceComplete()
	{
		stepChange = stepChange*(SCROLL_FRAMES-frameCount);
		lastFrame=0;
		step();
	}

	/* (non-Javadoc)
	 * @see gr.fire.core.Animation#stop()
	 */
	public void stop()
	{
		frameCount = SCROLL_FRAMES;
	}


	public int getDirection()
	{
		return direction;
	}


	public void setDirection(int direction)
	{
		this.direction = direction;
	}
}
