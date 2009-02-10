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
package gr.fire.core;

/**
 * 该布局管理器模仿J2SE中BoxLayout的行为，
 * 它允许多个组件中横向和纵向上进行布局。
 * 构造函数接受一个指定布局方向的参数，参数值如下：
 * X_AXIS - 组件将会横向布局
 * Y_AXIS - 组件将会纵向布局
 *
 * This layout manager mimics the behaiviour of the BoxLayout manager of j2se.
 * 
 * It allows multiple components to be laid out either vertically or horizontally. 
 * The BoxLayout manager is constructed with an axis parameter that specifies the type of layout that will be done. There are four choices:
 * X_AXIS - Components are laid out horizontally from left to right.
 * Y_AXIS - Components are laid out vertically from top to bottom.
 *
 * BoxLayout尝试将组件按组件的首选宽度（横向布局）或首选高度（纵向布局）进行排列
 * 横向布局时，如果组件的高度不相同，那么将会尝试将组件的高度设置为高度最大的那个组件的高度
 * 
 * BoxLayout attempts to arrange components at their preferred widths (for horizontal layout) or heights (for vertical layout). 
 * For a horizontal layout, if not all the components are the same height, BoxLayout attempts to make all the components 
 * as high as the highest component.
 * 
 * @author padeler
 *
 */
public class BoxLayout implements LayoutManager
{
    /**
     * 横向布局
     */
	public static final int X_AXIS=0;
    /**
     * 纵向布局
     */
	public static final int Y_AXIS=1;
	
	/**
     * 布局方向
     */
	private int axis;
	/**
     * 指定布局方法并构造新实例
     * @param axis 布局方向，其值为<code>BoxLayout.X_AXIS</code>或<code>BoxLayout.Y_AXIS</code>之一
     */
	public BoxLayout(int axis)
	{
		this.axis=axis;
	}

	/**
	 * @see gr.fire.core.LayoutManager#layoutContainer(gr.fire.core.Container)
	 */
	public void layoutContainer(Container parent)
	{
		switch(axis)
		{
		case X_AXIS:
			layoutXaxis(parent);
			break;
		case Y_AXIS:
			layoutYaxis(parent);
			break;
		}
	}
	/**
     * 进行横向布局
     * @param parent
     */
	private void layoutXaxis(Container parent)
	{
		// first find the width and the height of each slot.
		int w,h;
		
		layoutChildren(parent);// recursivelly layout children.
		
		int[] d = parent.getPrefSize();
		if(d==null) d = parent.getMinSize();
		
		w=d[0];
		h=d[1];
		
		parent.width=w;
		parent.height=h;
			
		int totalW=0,maxH=0;
		int splitCount = 0; // the number of components with no prefSize.
		// first get the preffered size of each component.
		for(int i =0 ;i <parent.components.size();++i)
		{
			Component cmp = (Component)parent.components.elementAt(i);
					
			d = cmp.getPrefSize();
			if(d==null) {
				d = cmp.getMinSize();
				splitCount++;// count how many components do not have set prefered dimensions. 
				// Any space left on the container will be split to these components
			}
			if(d!=null)
			{
				totalW += d[0];
				int cmpH = d[1]; 
				if(cmpH>maxH) maxH = cmpH;
			}
		}
		// now we need to adjust the components' sizes
		int adjustment = 0;
		int splitW=0;
		if(totalW>w)
		{
			adjustment = (1000 * w)/totalW;
		}
		else if(totalW<w)
		{
			if(splitCount==0) splitCount=1; // to avoid arithmetic exceptions
			splitW = (w-totalW)/splitCount;
		}
		
		if(maxH>h || maxH==0) maxH=h;
		int ypos = (h-maxH)/2;
		
		int xpointer=0;
		for(int i =0 ;i <parent.components.size();++i)
		{
			Component cmp = (Component)parent.components.elementAt(i);
			d = cmp.getPrefSize();
			if(d!=null)
			{
				int prefWidthInPixels = d[0];
				
				if(adjustment!=0)
				{
					cmp.width = (prefWidthInPixels * adjustment)/1000;
				}
				else cmp.width = prefWidthInPixels;
				
			}else
			{
				d = cmp.getMinSize();
				int tempW = splitW;
				
				if(d!=null) tempW+=d[0];
				cmp.width = tempW;
			}
			
			cmp.height=maxH;
			
			cmp.x = xpointer;
			cmp.y = ypos;
			
			xpointer += cmp.width;
		}
		
		// the last component also gets the remaining space (usually 1pixel)
		if(parent.components.size()>0)
		{
			Component cmp = (Component)parent.components.elementAt(parent.components.size()-1);
			if(cmp.x+cmp.width<w) cmp.width = w-cmp.x;
		}
	}
    /**
     * 进行纵向布局
     * @param parent
     */
	private void layoutYaxis(Container parent)
	{
		// first find the width and the height of each slot.
		int w,h;
		
		layoutChildren(parent);// recursivelly layout children.
		
		int []d = parent.getPrefSize();
		if(d==null) d = parent.getMinSize();
		
		w=d[0];
		h=d[1];

		parent.width=w;
		parent.height=h;
	
		int totalH=0,maxW=0;
		int splitCount = 0; // the number of components with no prefSize.
		// first get the preffered size of each component.
		for(int i =0 ;i <parent.components.size();++i)
		{
			Component cmp = (Component)parent.components.elementAt(i);
			
			d = cmp.getPrefSize();
			if(d==null) {
				d = cmp.getMinSize();
				splitCount++;// count how many components do not have set prefered dimensions. 
				// Any space left on the container will be split to these components
			}
			
			if(d!=null)
			{
				totalH += d[1];
				int cmpW = d[0]; 
				if(cmpW>maxW) maxW = cmpW;
			} 
		}
		// now we need to adjust the components' sizes
		int adjustment = 0;
		int splitH=0;
		if(totalH>h)
		{
			adjustment = (1000 * h)/totalH;
		}
		else if(totalH<h)
		{
			if(splitCount==0) splitCount=1; // to avoid arithmetic exceptions
			splitH = (h-totalH)/splitCount;
		}
		if(maxW>w || maxW==0) maxW=w;
		int xpos = (w-maxW)/2;
		int ypointer=0;
		for(int i =0 ;i <parent.components.size();++i)
		{
			Component cmp = (Component)parent.components.elementAt(i);
			d = cmp.getPrefSize();
			
			if(d!=null)
			{
				int prefHeightInPixels = d[1];
				
				if(adjustment!=0)
				{
					cmp.height = (prefHeightInPixels * adjustment)/1000;
				}
				else cmp.height = prefHeightInPixels;
			}else
			{
				d = cmp.getMinSize();
				int tmpH = splitH;
				if(d!=null) tmpH +=d[1];
				
				cmp.height = tmpH;
			}
			
			cmp.width=maxW;
			
			cmp.x = xpos;
			cmp.y = ypointer;
			
			ypointer += cmp.height;
		}
		
		// the last component also gets the remaining space (usually 1pixel)
		if(parent.components.size()>0)
		{
			Component cmp = (Component)parent.components.elementAt(parent.components.size()-1);
			if(cmp.y+cmp.height<h) cmp.height = h-cmp.y;
		}
	}
	

	public int getAxis()
	{
		return axis;
	}

	
	private void layoutChildren(Container cnt)
	{
		// if prefSize is null we must calculate it based on the childred of cnt.
		int maxW = 0,maxH=0;
		int totalW=0,totalH=0;
		for(int i=0;i<cnt.components.size();++i)
		{
			Component c = ((Component)cnt.components.elementAt(i));
			c.validate();
			if(c instanceof Container) // layout this container first.
			{
				Container childCnt = ((Container)c);
				childCnt.layoutManager.layoutContainer(childCnt);
			}
			
			int []prefSize = c.getPrefSize();
			
			if(prefSize!=null)
			{
				int w = prefSize[0];
				int h = prefSize[1];
				
				if(w>maxW) maxW = w;
				if(h>maxH) maxH = h;
				totalW += w;
				totalH += h;
			}
		}
		int w= totalW;
		int h= totalH;
		if(axis==X_AXIS) h = maxH;
		else if(axis==Y_AXIS) w = maxW;
		
		if(cnt.getPrefSize()==null)
		{
			cnt.setPrefSize(w,h);			
		}
	}
}
