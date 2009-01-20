package com.yinpsoft.beans;

import java.util.Date;
import java.util.HashSet;
import java.util.Set;

/**
 * Customertype entity. @author MyEclipse Persistence Tools
 */

public class Customertype implements java.io.Serializable {

	// Fields

	private String customertypeid;
	private String customertypecode;
	private String customertypename;
	private Date created;
	private Set customers = new HashSet(0);

	// Constructors

	/** default constructor */
	public Customertype() {
	}

	/** full constructor */
	public Customertype(String customertypecode, String customertypename,
			Date created, Set customers) {
		this.customertypecode = customertypecode;
		this.customertypename = customertypename;
		this.created = created;
		this.customers = customers;
	}

	// Property accessors

	public String getCustomertypeid() {
		return this.customertypeid;
	}

	public void setCustomertypeid(String customertypeid) {
		this.customertypeid = customertypeid;
	}

	public String getCustomertypecode() {
		return this.customertypecode;
	}

	public void setCustomertypecode(String customertypecode) {
		this.customertypecode = customertypecode;
	}

	public String getCustomertypename() {
		return this.customertypename;
	}

	public void setCustomertypename(String customertypename) {
		this.customertypename = customertypename;
	}

	public Date getCreated() {
		return this.created;
	}

	public void setCreated(Date created) {
		this.created = created;
	}

	public Set getCustomers() {
		return this.customers;
	}

	public void setCustomers(Set customers) {
		this.customers = customers;
	}

}