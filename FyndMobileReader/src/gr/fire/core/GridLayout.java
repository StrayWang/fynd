/**
 * 
 */
package gr.fire.core;

import java.util.Vector;

/**
 * Lays out the components of a container on a grid with given rows and columns.
 *  
 * This manager will layout the components of the container given to it in the layoutContainer method.
 * The Components will be layedout on the container from left-to-right and top-to-bottom. 
 * If the container has less components than (rows*columns) it will leave empty slots on the bottom of the container.
 * If the container has more components than (rows*columns) the manager will layout only (rows*columns) components, the remaining
 * components will be left unchanged.
 *  
 * @author padeler
 *
 */
public class GridLayout implements LayoutManager
{
	
	private int rows,columns;
	private int hgap,vgap;
	
	
	public GridLayout()
	{
		this(1,1,0,0);
	}
	
	public GridLayout(int rows,int columns)
	{
		this(rows,columns,0,0);
	}
	
	public GridLayout(int rows,int columns,int hgap,int vgap)
	{
		if(rows<1 || columns<1 || hgap<0 || vgap<0) throw new IllegalArgumentException("Illegal arguments on GridLayout manager");
		this.rows=rows;
		this.columns=columns;
		this.hgap=hgap;
		this.vgap=vgap;
	}

	/**
	 * @see gr.fire.core.LayoutManager#layoutContainer(gr.fire.core.Container)
	 */
	public void layoutContainer(Container parent)
	{
		// first find the width and the height of each slot.
		int w,h;
		
		layoutChildren(parent);// recursivelly layout children.
		
		int []d = parent.getPrefSize();
		
		if(parent.parent==null) // top level component.
		{
			FireScreen sc = FireScreen.getScreen();
			w=sc.getWidth();
			h=sc.getHeight();
		}
		else 			
		{
			w=d[0];
			h=d[1];
		}
		parent.width=w;
		parent.height=h;
	


		int slotW = (w-hgap*(columns-1))/columns;
		int slotH = (h-vgap*(rows-1))/rows;
		
		Vector components = parent.components;
		// now set the components positions and sizes.
		for(int r=0;r<rows;++r)
		{
			for(int c=0;c<columns;++c)
			{
				// get component
				int elementId = r*columns + c;
				if(elementId>=components.size()) break;
				
				Component cmp = (Component)components.elementAt(elementId);
				// layout the component.
				cmp.width=slotW;
				cmp.height=slotH;
				cmp.x = c*(slotW+hgap);
				cmp.y = r*(slotH+vgap);
			}
		}	
	}
	
	
	private void layoutChildren(Container cnt)
	{
		// if prefSize is null we must calculate it based on the childred of cnt.
		int maxW = 0,maxH=0;
		for(int i=0;i<cnt.components.size();++i)
		{
			Component c = ((Component)cnt.components.elementAt(i));
			if(c instanceof Container) // layout this container first.
			{
				Container childCnt = ((Container)c);
				childCnt.layoutManager.layoutContainer(childCnt);
			}
			int []ps=c.getPrefSize();
			if(ps!=null)
			{
				if(ps[0]>maxW) maxW = ps[0];
				if(ps[1]>maxH) maxH = ps[1];
			}

		}
		
		if(cnt.getPrefSize()==null)
		{			
			cnt.setPrefSize(maxW*columns,maxH*rows);			
		}
	}

	public int getRows()
	{
		return rows;
	}

	public int getColumns()
	{
		return columns;
	}

	public int getHgap()
	{
		return hgap;
	}

	public int getVgap()
	{
		return vgap;
	}

}
