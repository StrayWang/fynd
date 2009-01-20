package com.yinpsoft.daos;

import java.util.List;

import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;
import org.hibernate.Criteria;
import org.hibernate.LockMode;
import org.hibernate.Query;
import org.hibernate.criterion.Example;
import org.hibernate.criterion.Projections;

import com.yinpsoft.BaseDAO;
import com.yinpsoft.beans.Customertype;

/**
 * A data access object (DAO) providing persistence and search support for
 * Customertype entities. Transaction control of the save(), update() and
 * delete() operations can directly support Spring container-managed
 * transactions or they can be augmented to handle user-managed Spring
 * transactions. Each of these methods provides additional information for how
 * to configure it for the desired type of transaction control.
 * 
 * @see com.yinpsoft.beans.CustomerType
 * @author MyEclipse Persistence Tools
 */

public class CustomerTypeDAO extends BaseDAO {
	private static final Log log = LogFactory.getLog(CustomerTypeDAO.class);
	// property constants
	public static final String CUSTOMERTYPECODE = "customertypecode";
	public static final String CUSTOMERTYPENAME = "customertypename";

	public void save(Customertype transientInstance) {
		log.debug("saving Customertype instance");
		try {
			getSession().save(transientInstance);
			
			log.debug("save successful");
		} catch (RuntimeException re) {
			log.error("save failed", re);
			throw re;
		}
	}

	public void delete(Customertype persistentInstance) {
		log.debug("deleting Customertype instance");
		try {
			getSession().delete(persistentInstance);
			log.debug("delete successful");
		} catch (RuntimeException re) {
			log.error("delete failed", re);
			throw re;
		}
	}

	public Customertype findById(java.lang.String id) {
		log.debug("getting Customertype instance with id: " + id);
		try {
			Customertype instance = (Customertype) getSession().get(
					"com.yinpsoft.beans.Customertype", id);
			return instance;
		} catch (RuntimeException re) {
			log.error("get failed", re);
			throw re;
		}
	}
	
	public int getRecordCount(){
		try {
			Criteria query = getSession().createCriteria(Customertype.class);			
			return new Integer(query.setProjection(Projections.alias(Projections.rowCount(), "rowCount")).uniqueResult().toString());
		} catch (RuntimeException e) {
			log.error("get failed:"+e.getMessage());
			throw e;
		}
	}
	
	public List<Customertype> findByPage(int start, int end) {
		
		try {
			Criteria query = getSession().createCriteria(Customertype.class);
			query.setFirstResult(start);
			query.setMaxResults(end-start);
			return (List<Customertype>)query.list();
		} catch (RuntimeException e) {
			log.error("get failed:"+e.getMessage());
			throw e;
		}
	}

	public List findByExample(Customertype instance) {
		log.debug("finding Customertype instance by example");
		try {
			List results = getSession().createCriteria(
					"com.yinpsoft.beans.Customertype").add(
					Example.create(instance)).list();
			log.debug("find by example successful, result size: "
					+ results.size());
			return results;
		} catch (RuntimeException re) {
			log.error("find by example failed", re);
			throw re;
		}
	}

	public List findByProperty(String propertyName, Object value) {
		log.debug("finding Customertype instance with property: "
				+ propertyName + ", value: " + value);
		try {
			String queryString = "from Customertype as model where model."
					+ propertyName + "= ?";
			Query queryObject = getSession().createQuery(queryString);
			queryObject.setParameter(0, value);
			return queryObject.list();
		} catch (RuntimeException re) {
			log.error("find by property name failed", re);
			throw re;
		}
	}

	public List findByCustomertypecode(Object customertypecode) {
		return findByProperty(CUSTOMERTYPECODE, customertypecode);
	}

	public List findByCustomertypename(Object customertypename) {
		return findByProperty(CUSTOMERTYPENAME, customertypename);
	}

	public List findAll() {
		log.debug("finding all Customertype instances");
		try {
			String queryString = "from Customertype";
			Query queryObject = getSession().createQuery(queryString);
			return queryObject.list();
		} catch (RuntimeException re) {
			log.error("find all failed", re);
			throw re;
		}
	}

	public Customertype merge(Customertype detachedInstance) {
		log.debug("merging Customertype instance");
		try {
			Customertype result = (Customertype) getSession().merge(
					detachedInstance);
			log.debug("merge successful");
			return result;
		} catch (RuntimeException re) {
			log.error("merge failed", re);
			throw re;
		}
	}

	public void attachDirty(Customertype instance) {
		log.debug("attaching dirty Customertype instance");
		try {
			getSession().saveOrUpdate(instance);
			log.debug("attach successful");
		} catch (RuntimeException re) {
			log.error("attach failed", re);
			throw re;
		}
	}

	public void attachClean(Customertype instance) {
		log.debug("attaching clean Customertype instance");
		try {
			getSession().lock(instance, LockMode.NONE);
			log.debug("attach successful");
		} catch (RuntimeException re) {
			log.error("attach failed", re);
			throw re;
		}
	}
}