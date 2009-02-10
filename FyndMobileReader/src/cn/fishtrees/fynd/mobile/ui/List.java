/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package cn.fishtrees.fynd.mobile.ui;

import gr.fire.core.Component;
import gr.fire.core.Container;
import gr.fire.core.LayoutManager;

import java.util.Enumeration;
import java.util.Vector;

/**
 * 
 * @author fishtrees
 */
public class List extends Container {

	protected Vector items;
	public List(LayoutManager manager) {
		super(manager);
		this.items = new Vector();
	}

	public void add(Component cmp) {
		if (!(cmp instanceof ListItem)) {
			throw new IllegalArgumentException(
					"Only ListItem can be added to List");
		}
		this.add((ListItem)cmp);
	}

	public void add(ListItem li) {
		super.add(li);
		this.items.addElement(li);
	}

	public void removeAll() {
		Enumeration en = this.items.elements();
		while(en.hasMoreElements()){
			super.remove((Component)en.nextElement());
		}
		this.items.removeAllElements();
	}

	public void remove(Component cmp) {
		super.remove(cmp);
		this.items.removeElement(cmp);
	}

	public ListItem getSelectedItem() {
		for (int i = 0; i < this.components.size(); i++) {
			ListItem li = (ListItem) this.components.elementAt(i);
			if (li.isSelected()) {
				return li;
			}
		}
		return null;
	}
}
