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

import gr.fire.ui.ScrollAnimation;
import gr.fire.util.Log;

import java.util.Vector;

import javax.microedition.lcdui.Canvas;
import javax.microedition.lcdui.Font;
import javax.microedition.lcdui.Graphics;
import javax.microedition.lcdui.Image;

/**
 * A Panel is a special type of Container. It can contain container. 
 * 
 * @author padeler
 * 
 */
public class Panel extends Container
{
	public static final int FAST_SCROLL_PERCENT=60;
	public static final int NORMAL_SCROLL_PERCENT=35;
	

	public static final int VERTICAL_SCROLLBAR = 0x00000001;
	public static final int HORIZONTAL_SCROLLBAR = 0x00000100;
	public static final int NO_SCROLLBAR = 0x00000000;

	private int scrollBarPolicy = 0x00000000;// no scrollbars
	private Container container = null;
	private boolean showDecorations = false;
	private int scrollX,scrollY;
	
	private boolean showBackground=false;
	
	
	int viewPortWidth, viewPortHeight;
	
	private boolean closeOnOutofBoundsPointerEvents=true; // controls if this panel should close when the user taps outside its bounding box. 

	private Theme theme;
	
	private String label;
	private int labelX=0,labelY=0;

	private Image decorTopImage, decorBottomImage,backgroundTexture;

	private Vector focusableComponents = null;
	
	private int decorLeft=0,decorTop=0,decorRight,decorBottom;
	
	private int dragX=-1,dragY=-1;
	private boolean dragScroll=false;
	
	public Panel(Container cnt, int scrollbarPolicy, boolean showDecorations)
	{
		setFocusable(true);
		this.scrollBarPolicy = scrollbarPolicy;
		this.showDecorations = showDecorations;
		if(cnt!=null)
			set(cnt);

		theme = FireScreen.getTheme();
	}

	public Panel(int scrollBarPolicy)
	{
		this(null, scrollBarPolicy, false);
	}

	public Panel()
	{
		this(null, 0x00000000, false);
	}

	public void paint(Graphics g)
	{
		int originalTrX = g.getTranslateX();
		int originalTrY = g.getTranslateY();
		int originalClipX = g.getClipX();
		int originalClipY = g.getClipY();
		int originalClipWidth = g.getClipWidth();
		int originalClipHeight = g.getClipHeight();

		Component cmp = container;
		if (cmp != null) // draw only visible components
		{
			if (cmp.valid == false)
			{ // my component needs validation.
				if((container instanceof Panel)==false)
					container.layoutManager.layoutContainer(container); // layout the container.
				container.validate(); // validate the container
			}
			
			if(showBackground)
			{
				if(backgroundTexture==null)
					backgroundTexture = theme.getBackgroundTexture(viewPortWidth,viewPortHeight);
				if(backgroundTexture!=null)
					g.drawImage(backgroundTexture,decorLeft,decorTop,Graphics.TOP|Graphics.LEFT);
			}

			if (cmp.visible && cmp.intersects(originalClipX, originalClipY, originalClipWidth, originalClipHeight))
			{
				if(showDecorations)
					g.clipRect(decorLeft, decorTop, viewPortWidth, viewPortHeight); // clip to the viewport
					
				g.clipRect(cmp.x, cmp.y, cmp.width, cmp.height);
				g.translate(cmp.x, cmp.y);

				if (cmp.animation == null)
					cmp.paint(g);
				else
					cmp.animation.paint(g);

				// return to the coordinates of this component.
				g.translate(originalTrX - g.getTranslateX(), originalTrY - g.getTranslateY());
				g.setClip(originalClipX, originalClipY, originalClipWidth, originalClipHeight);
			}
		}
		
		if (showDecorations) // this panel has decorations
		{
			if(originalClipY < decorTop)
				drawDecorTop(g);
			if((originalClipY + originalClipHeight) > (height - decorBottom))
				drawDecorBottom(g);
			if(originalClipX < decorLeft)
				drawDecorLeft(g);
			if((originalClipX + originalClipWidth) > (width - decorRight))
				drawDecorRight(g);
			Image logo = theme.getLogo();
			if(logo!=null)
			{
				int lx=0,ly=0;
				switch (theme.getIntProperty("logo.icon.valign"))
				{
					case FireScreen.TOP:
						ly=0;
						break;
					case FireScreen.BOTTOM:
						ly = getHeight()-logo.getHeight();
						break;
					case FireScreen.VCENTER:
						ly = (getHeight()-logo.getHeight())/2;
						break;
				}
				switch (theme.getIntProperty("logo.icon.align"))
				{
					case FireScreen.LEFT:
						lx=0;
						break;
					case FireScreen.RIGHT:
						lx= getWidth()-logo.getWidth();
						break;
					case FireScreen.CENTER:
						lx= (getWidth()-logo.getWidth())/2;
						break;
				}
				g.drawImage(logo,lx,ly,Graphics.TOP|Graphics.LEFT);
			}
			if(label!=null)
			{
				Font labelFont = theme.getFontProperty("label.font"); 
				g.setFont(labelFont);
				g.setColor(theme.getIntProperty("titlebar.fg.color"));
				int lw = labelFont.stringWidth(label);
				g.drawString(label, labelX, labelY, Graphics.TOP | Graphics.LEFT);
			}
		}
		
		if(border)
		{
			g.setColor(theme.getIntProperty("border.color"));
			g.drawRect(0,0,width-1,height-1);
		}
		
		drawScrollbars(g);
	}
	
	public String getLabel()
	{
		return label;
	}

	public void setLabel(String label)
	{
		this.label=label;
		if(label!=null)
		{// find the labelX and labelY.
			int align = theme.getIntProperty("label.align");
			int valign = theme.getIntProperty("label.valign");
			Font f = theme.getFontProperty("label.font");
			int strLen = f.stringWidth(label);
			int strHeight = f.getHeight();
			
			switch(align)
			{
			case FireScreen.CENTER:
				labelX= FireScreen.getScreen().getWidth()/2-strLen/2;
				break;
			case FireScreen.RIGHT:
				labelX= FireScreen.getScreen().getWidth()-strLen;
				break;
			default:
				labelX=0;
			}
			switch(valign)
			{
			case FireScreen.VCENTER:
				labelY= FireScreen.getScreen().getHeight()/2-strHeight/2;
				break;
			case FireScreen.BOTTOM:
				labelY= FireScreen.getScreen().getHeight()-strHeight;
				break;
			default:
				labelY=0;
			}
		}
	}
	
	private void drawScrollbars(Graphics g)
	{
		if(container!=null)
		{
			if ((scrollBarPolicy & VERTICAL_SCROLLBAR) == VERTICAL_SCROLLBAR && container.height > viewPortHeight)
			{ // draw vertical scrollbar
				int rightHeight = height - decorBottom - decorTop;
				g.setColor(theme.getIntProperty("scrollbar.color"));
				int vpPosY = getViewPortPositionY();
				scrollY = (rightHeight * (100 * (vpPosY + viewPortHeight / 2)) / container.height) / 100;
				int tl = theme.scrollLenght / 2;
				if (scrollY < tl || vpPosY == 0)
					scrollY = tl;
				else if (scrollY > rightHeight - tl || vpPosY == container.height - viewPortHeight)
					scrollY = rightHeight - tl;
		
				g.fillRect(width - theme.scrollSize + 1, decorTop + scrollY - tl, theme.scrollSize - 1, theme.scrollLenght);
			}		
	
			if ((scrollBarPolicy & HORIZONTAL_SCROLLBAR) == HORIZONTAL_SCROLLBAR && container.width > viewPortWidth)
			{ // draw vertical scrollbar
				int bottomWidth = width - decorLeft - decorRight;
				// draw scroll bar area.
				g.setColor(theme.getIntProperty("scrollbar.color"));
				int vpPosX = getViewPortPositionX();
				scrollX = (bottomWidth * (100 * (vpPosX + viewPortWidth / 2)) / container.width) / 100;
				int tl = theme.scrollLenght / 2;
				if (scrollX < tl || vpPosX == 0)
					scrollX = tl;
				else if (scrollX > bottomWidth - tl || vpPosX == container.width - viewPortWidth)
					scrollX = bottomWidth - tl;
	
				g.fillRect(decorLeft + scrollX - tl, height - decorBottom + 1, theme.scrollLenght, theme.scrollSize - 1);
			}
		}
	}

	private void drawDecorTop(Graphics g)
	{
		if (decorTop == 0)
			return;
		if (decorTopImage == null)
		{
			decorTopImage = theme.getTitlebarTexture(width, decorTop);
		}
		g.drawImage(decorTopImage, 0, 0, Graphics.TOP | Graphics.LEFT);

	}

	private void drawDecorBottom(Graphics g)
	{
		if (decorBottom == 0)
			return;

		if (decorBottomImage == null)
		{
			decorBottomImage = theme.getNavbarTexture(width, decorBottom);
		}
		g.drawImage(decorBottomImage, 0, height - decorBottom, Graphics.TOP | Graphics.LEFT);
	}

	private void drawDecorLeft(Graphics g)
	{
		// decor left not supported by this panel implementation.
	}

	private void drawDecorRight(Graphics g)
	{
		// decor right not supported by this panel implementation.
	}

	public void validate()
	{
		theme = FireScreen.getTheme();
		if(showDecorations)
		{
			decorLeft = theme.decorLeft;
			decorTop = theme.decorTop;
			decorBottom = theme.decorBottom;
			decorRight = theme.decorRight;
		}
		else
		{
			decorLeft=0;
			decorTop=0;
			decorBottom=0;
			decorRight=0;
		}
		
		focusableComponents=null;
		decorBottomImage = null;
		decorTopImage = null;

		int[] d = getPrefSize();
		if (d == null)
		{
			d = getMinSize();
		}
		width = d[0];
		height = d[1];


		if (container == null)
		{
			valid = true;
			return;
		}
		
		viewPortHeight = height - decorBottom - decorTop;
		viewPortWidth = width - decorLeft - decorRight;
		container.x = decorLeft;
		container.y = decorTop;

		if (scrollBarPolicy == NO_SCROLLBAR)
		{ // no scrollbars, container is bounded on both dimensions
			container.setPrefSize(viewPortWidth, viewPortHeight);
			container.layoutManager.layoutContainer(container); // layout the
																// container.
			container.validate(); // validate the container
			valid = true;
			return;
		}

		if (container.getPrefSize() == null)
		{ // let the layout manager calculate the prefSize.
			container.layoutManager.layoutContainer(container); // layout the
																// container.
		}

		int []tmpPs = container.getPrefSize();
		int []ps = new int[]{tmpPs[0], tmpPs[1]};
		if (ps[1]< viewPortHeight)
			ps[1]= viewPortHeight;
		if (ps[0]< viewPortWidth)
			ps[0]= viewPortWidth;

		// container.width=viewPortWidth;
		// container.height=viewPortHeight;
	
		if ((scrollBarPolicy & VERTICAL_SCROLLBAR) != VERTICAL_SCROLLBAR) // bounded
																			// vertically
		{ // show verticall scrollbars this means that the container has
			// unbounded height
			ps[1] = viewPortHeight;
		}

		if ((scrollBarPolicy & HORIZONTAL_SCROLLBAR) != HORIZONTAL_SCROLLBAR) // bounded
																				// horizontally
		{ // show horizontal scrollbars this means that the container has
			// unbounded width
			ps[0] = viewPortWidth;
		}
		container.setPrefSize(ps[0],ps[1]); // set the new prefered size
		container.layoutManager.layoutContainer(container); // layout the
															// container.
		container.validate(); // validate the container
		valid = true;
//
//		Log.logInfo("My Dimensions: " + width + "," + height);
//		Log.logInfo("ViewPort Dimensions: " + viewPortWidth + "," + viewPortHeight);
//		Log.logInfo("Container Dimensions: " + container.width + "," + container.height);
	}

	/**
	 * A Panel can have at most one container at any given time.
	 * 
	 * @param container
	 */
	public void set(Container container)
	{
		if(container==null) throw new IllegalArgumentException("Cannot set a null container to a Panel. Use remove() instead.");
		if (this.container != null)
			this.container.parent = null;

		this.container = container;
		
		if (container.parent != null)
		{
			if (container.parent instanceof Container)
				((Container) container.parent).remove(container);
		}
		container.parent = this;
		valid = false;
	}

	public void remove(Component c)
	{
		if (container != c)
		{
			throw new IllegalArgumentException("Container "+c+" is not inside this panel.");
		}
		else if(container!=null)
		{
			container.parent = null;
			container = null;
			valid = false;
		}
	}
	
	public int countComponents()
	{
		if(container!=null)
			return container.countComponents();
		else return 0;
	}
	
	

	/**
	 * 
	 * Sets the viewport position relative to the top left corner of the
	 * container inside this panel.
	 * 
	 * 
	 * @param x
	 *            the distance (in pixels) of the left side of the viewport from
	 *            the left side of the container
	 * @param y
	 *            the distance (in pixels) of the top side of the viewport from
	 *            the top side of the container
	 */
	public boolean setViewPortPosition(int x, int y)
	{
		if (container == null)
			return false;

		int tx = decorLeft - container.x;
		int ty = decorTop - container.y;


		if (x + viewPortWidth > container.width)
			x = container.width - viewPortWidth;
		else if (x < 0)
			x = 0;
		if (y + viewPortHeight > container.height)
			y = container.height - viewPortHeight;
		else if (y < 0)
			y = 0;
		
		if(x==tx && y==ty) // nothing changed return false
		{
			return false;
		}
		
		// ok now set the correct offset to the container.
		container.x = decorLeft - x;
		container.y = decorTop - y;
		repaint();
		return true; // change in the viewport position was successfull. 
	}

	public int getViewPortPositionX()
	{
		if (container == null)
			return 0;

		return decorLeft - container.x;
	}

	public int getViewPortPositionY()
	{
		if (container == null)
			return 0;
		return decorTop - container.y;
	}

	public int getViewPortWidth()
	{
		return viewPortWidth;
	}

	public int getViewPortHeight()
	{
		return viewPortHeight;
	}

	protected void pointerDragged(int x, int y)
	{
		if (container != null)
		{
			if(dragScroll && dragX==-1 && dragY==-1) // intercept drag events end use them for navigation.
			{ // keep the starting drag point. when the pointer is released a pointer event will be sent.
				dragX = x;
				dragY = y;
			}
			else if (x > decorLeft && x < width - decorRight && y > decorTop && y < height - decorBottom)
			{
				container.pointerDragged(x - container.x, y - container.y); 
			}
		}
		super.pointerDragged(x,y);
	}

	protected void pointerPressed(int x, int y)
	{
		if (container != null)
		{
			if (x > decorLeft && x < width - decorRight && y > decorTop && y < height - decorBottom)
			{
				container.pointerPressed(x - container.x, y - container.y);
			}
		}
		super.pointerPressed(x,y);
	}

	protected void pointerReleased(int x, int y)
	{
		if(closeOnOutofBoundsPointerEvents && (x<0 || x>width || y<0 || y>height)) FireScreen.getScreen().removeComponent(this);
		
		if(container != null)
		{
			if(dragScroll && dragX!=-1 && dragY!=-1)
			{ // drag event.
				int dx = x - dragX;
				int dy = y - dragY;
				
				Log.logDebug("In Panel.pointerReleased(). DRAG: "+ dragX +","+dragY+" ==> DIFFX / DIFFY "+dx+","+dy);
				
				dragX=-1;dragY=-1;
				if(animation==null)
				{
					if(dy<-10) scrollDown(true);
					else if(dy>10) scrollUp(true);
					else if(dx<-10) scrollRight(true);
					else if(dx>10) scrollLeft(true);
				}
			}
			else
			{
				int rightMargin = decorRight;
				if((scrollBarPolicy & VERTICAL_SCROLLBAR) == VERTICAL_SCROLLBAR && rightMargin<2*theme.scrollSize)
				{
					rightMargin = 2*theme.scrollSize;
				}
				
				if (x > decorLeft && x < width - rightMargin && y > decorTop && y < height - decorBottom)
				{
					container.pointerReleased(x - container.x, y - container.y);
				}
				else
				{
					if (((scrollBarPolicy & VERTICAL_SCROLLBAR) == VERTICAL_SCROLLBAR) && x > width - rightMargin && y < height - decorBottom)
					{ // check if click is on scrollbar
						if (y > scrollY ) // scroll down
						{
							scrollDown(false);
						} else
							// scroll up
						{
							scrollUp(false);
						}
					} else if (((scrollBarPolicy & HORIZONTAL_SCROLLBAR) == HORIZONTAL_SCROLLBAR) && y > (height - decorBottom-theme.scrollSize) && y < height - decorBottom + theme.scrollSize)
					{
						if (x > scrollX)
						{
							scrollRight(false);
						} else
						{
							scrollLeft(false);
						}
					}
					else if(y>height-decorBottom)// click on softkeys.
					{
						if(x<width/3)
							super.keyReleased(FireScreen.leftSoftKey); 
						else if(x>(width/3)*2)
							super.keyReleased(FireScreen.rightSoftKey);
					}
				}
			}
		}
		if(pointerListener!=null) pointerListener.pointerReleased(x,y,this);
	}

	public void scrollDown(boolean fast)
	{
		if ((scrollBarPolicy & VERTICAL_SCROLLBAR) != VERTICAL_SCROLLBAR)
			return;
		ScrollAnimation anim = new ScrollAnimation(this,FireScreen.DOWN,(fast?FAST_SCROLL_PERCENT:NORMAL_SCROLL_PERCENT));
		if(this.animation!=null)
		{ // do not scroll with animation if thereis another running.
			anim.forceComplete();
		}
		else
		{
			FireScreen.getScreen().registerAnimation(anim);
		}
	}

	public void scrollUp(boolean fast)
	{
		if ((scrollBarPolicy & VERTICAL_SCROLLBAR) != VERTICAL_SCROLLBAR)
			return;
		ScrollAnimation anim = new ScrollAnimation(this,FireScreen.UP,(fast?FAST_SCROLL_PERCENT:NORMAL_SCROLL_PERCENT));
		if(this.animation!=null)
		{ // do not scroll with animation if thereis another running.
			anim.forceComplete();
		}
		else
		{
			FireScreen.getScreen().registerAnimation(anim);
		}
	}

	public void scrollLeft(boolean fast)
	{
		if ((scrollBarPolicy & VERTICAL_SCROLLBAR) != VERTICAL_SCROLLBAR)
			return;
		ScrollAnimation anim = new ScrollAnimation(this,FireScreen.LEFT,(fast?FAST_SCROLL_PERCENT:NORMAL_SCROLL_PERCENT));
		if(this.animation!=null)
		{ // do not scroll with animation if thereis another running.
			anim.forceComplete();
		}
		else
		{
			FireScreen.getScreen().registerAnimation(anim);
		}
	}

	public void scrollRight(boolean fast)
	{
		if ((scrollBarPolicy & VERTICAL_SCROLLBAR) != VERTICAL_SCROLLBAR)
			return;
		ScrollAnimation anim = new ScrollAnimation(this,FireScreen.RIGHT,(fast?FAST_SCROLL_PERCENT:NORMAL_SCROLL_PERCENT));
		if(this.animation!=null)
		{ // do not scroll with animation if thereis another running.
			anim.forceComplete();
		}
		else
		{
			FireScreen.getScreen().registerAnimation(anim);
		}
	}

	protected void keyPressed(int keyCode)
	{
		if (container != null)
			container.keyPressed(keyCode);

		if(keyListener!=null) keyListener.keyPressed(keyCode,this);
	}

	protected void keyReleased(int keyCode)
	{
		if (keyCode == FireScreen.leftSoftKey || keyCode == FireScreen.rightSoftKey)
		{
			super.keyReleased(keyCode);
			return;
		}
		if (container != null)
		{
			FireScreen screen = FireScreen.getScreen();
			int gameCode = screen.getGameAction(keyCode);

			
			if(gameCode==Canvas.LEFT)
			{
				if((scrollBarPolicy&HORIZONTAL_SCROLLBAR)==HORIZONTAL_SCROLLBAR)
					scrollLeft(false);
				else scrollUp(true);
				
			}
			else if(gameCode==Canvas.RIGHT)
			{
				if((scrollBarPolicy&HORIZONTAL_SCROLLBAR)==HORIZONTAL_SCROLLBAR)
					scrollRight(false);
				else scrollDown(true);
			}
			else if (gameCode == Canvas.UP || gameCode == Canvas.DOWN )
			{			
				// first find the next selectable component.
				if(focusableComponents==null)
					focusableComponents = container.generateListOfFocusableComponents(true);

				int step;
				int index;
				if (gameCode == Canvas.UP)
				{
					step = -1; // previous component
					index=focusableComponents.size()-1;
				}	
				else
				{
					step = +1; // next component
					index=0;
				}
				int vpx = getViewPortPositionX();
				int vpy = getViewPortPositionY();
				int vpmx = vpx+viewPortWidth;
				int vpmy = vpy + viewPortHeight;
				
				Component lastSelected = screen.getSelectedComponent();
				
				if(lastSelected!=null)
				{
					int lastPos = focusableComponents.indexOf(lastSelected);
					if(lastPos>-1)
					{
						index = lastPos+step;
					}
					if((index<0 || index>focusableComponents.size()-1) && (vpy==0 || vpmy>=container.height)) 
					{// end of visible components vector. Disselect last selected						
						screen.setSelectedComponent(null);
					}		
				}
				
				boolean scroll=true;
				// try to find the first component inside the view port				
				for(int i=index;i>-1 && i<focusableComponents.size();i +=step)
				{
					Component next = (Component)focusableComponents.elementAt(i);
					// check to see if the component is visible
					int realx=0,realy=0,fh;
					Component tmp = next;
					Font f = next.getFont();
					if(f==null) f = FireScreen.getTheme().getFontProperty("font");
					
					fh = f.getHeight()/2;
					
					while(tmp!=null && tmp!=container)
					{
						realx+=tmp.x;
						realy+=tmp.y;
						tmp = tmp.parent;
					}
					if(realx>=vpx && realy>=vpy && realx<(vpmx) && realy<(vpmy-fh))
					{ // component is visible, send the event to it.						
						next.keyReleased(keyCode);
						screen.setSelectedComponent(next);
						scroll=false;
						break;
					}			
				}

				// no selectable component found. just scroll
				if(scroll)
				{// just scroll
					if(gameCode==Canvas.UP)
						scrollUp(false);
					else if(gameCode==Canvas.DOWN)
						scrollDown(false);
					else if(gameCode==Canvas.LEFT)
						scrollUp(true);
					else if(gameCode==Canvas.RIGHT)
						scrollDown(true);
				}				
			}
		}
		if(keyListener!=null) keyListener.keyReleased(keyCode,this);
	}
		
	protected void keyRepeated(int keyCode)
	{
		if (container != null)
		{
			int gameCode = FireScreen.getScreen().getGameAction(keyCode);

			if(gameCode==Canvas.LEFT)
			{
				scrollLeft(false);
				return;
			}
			if(gameCode==Canvas.RIGHT)
			{
				scrollRight(false);
				return;
			}
			
			if(gameCode==Canvas.UP)
			{
				scrollUp(false);
				return;
			}
			if(gameCode==Canvas.DOWN)
			{
				scrollDown(false);
				return;
			}
			
			container.keyRepeated(keyCode);
		}
		if(keyListener!=null) keyListener.keyRepeated(keyCode,this);
	}
	
	public Vector generateListOfFocusableComponents(boolean recursive)
	{
		if(container!=null) return container.generateListOfFocusableComponents(recursive);
		else return new Vector();
	}
	

	public int[] getMinSize()
	{
		if (parent == null)
		{
			FireScreen screen = FireScreen.getScreen();
			return new int[]{screen.getWidth(), screen.getHeight()};
		}
		return super.getMinSize();
	}

	public int getScrollBarPolicy()
	{
		return scrollBarPolicy;
	}

	public boolean isCloseOnOutofBoundsPointerEvents()
	{
		return closeOnOutofBoundsPointerEvents;
	}

	public void setCloseOnOutofBoundsPointerEvents(boolean closeOnOutofBoundsPointerEvents)
	{
		this.closeOnOutofBoundsPointerEvents = closeOnOutofBoundsPointerEvents;
	}

	public boolean isDragScroll()
	{
		return dragScroll;
	}

	public void setDragScroll(boolean dragScroll)
	{
		this.dragScroll = dragScroll;
	}

	public boolean isShowBackground()
	{
		return showBackground;
	}

	public void setShowBackground(boolean showBackground)
	{
		this.showBackground = showBackground;
	}

	public boolean isShowDecorations()
	{
		return showDecorations;
	}

	public void setShowDecorations(boolean showDecorations)
	{
		this.showDecorations = showDecorations;
	}
}
